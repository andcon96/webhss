<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Domain;
use App\Models\Master\Item;
use App\Models\Master\Prefix;
use App\Models\Master\ShipFrom;
use App\Models\Transaksi\CustomerOrderDetail;
use App\Models\Transaksi\CustomerOrderMstr;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SalesOrderDetail;
use App\Models\Transaksi\SuratJalan;
use App\Services\CreateTempTable;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $cust = Customer::get();
        $shipfrom = ShipFrom::get();
        $shipto = CustomerShipTo::get();
        $custord = CustomerOrderMstr::get();
        $salesord = SalesOrderMstr::get();
        $data = SalesOrderMstr::query()
                              ->with('getDetail',
                                     'getCOMaster.getCustomer',
                                     'getShipFrom',
                                     'getShipTo');

        if($request->s_sonumber){
            $data->where('id',$request->s_sonumber);
        }
        if($request->s_conumber){
            $data->whereRelation('getCOMaster','id',$request->s_conumber);
        }
        if($request->s_customer){
            $data->whereRelation('getCOMaster.getCustomer','co_cust_code',$request->s_customer);
        }
        if($request->s_shipto){
                $data->where('so_ship_to',$request->s_shipto);
        }
        if($request->s_shipfrom){
            $request->s_shipfrom == 'null' ? 
                $data->whereNull('so_ship_from') : 
                $data->where('so_ship_from',$request->s_shipfrom);
        }
        if($request->s_status){
            $data->where('so_status',$request->s_status);
        }

        $data = $data->orderBy('created_at','DESC')->paginate(10);

        return view('transaksi.salesorder.index',compact('cust','shipfrom','shipto','custord','salesord','data'));       
    }

    public function create()
    {
        $item = Item::get();
        $cust = Customer::get();
        $shipfrom = ShipFrom::where('sf_is_active',1)->get();
        $conbr = CustomerOrderMstr::query()
                        ->with('getCustomer','getDetail')
                        ->whereRelation('getDetail',function ($q){
                            $q->whereRaw('cod_qty_ord > cod_qty_used');
                        })
                        ->get();
                        
        return view('transaksi.salesorder.create',compact('item','cust','conbr','shipfrom'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $prefix = Prefix::lockForUpdate()->first();

            $getrn = (new CreateTempTable())->getrnso();
            if($getrn === false){
                alert()->error('Error', 'Failed to create SO')->persistent('Dismiss');
                DB::rollBack();
                return back();
            }
            
            $so_mstr = new SalesOrderMstr();
            $so_mstr->so_nbr = $getrn;
            $so_mstr->so_co_mstr_id = $request->conbr;
            $so_mstr->so_ship_from = $request->shipfrom;
            $so_mstr->so_ship_to = $request->shipto;
            $so_mstr->so_due_date = $request->duedate;
            $so_mstr->save();

            $id = $so_mstr->id;
            foreach($request->line as $key => $datas){
                if($request->qtyord[$key] != 0){
                    $so_detail = new SalesOrderDetail();
                    $so_detail->sod_so_mstr_id = $id;
                    $so_detail->sod_line = $datas;
                    $so_detail->sod_part = $request->part[$key];
                    $so_detail->sod_um = $request->um[$key];
                    $so_detail->sod_qty_ord = $request->qtyord[$key];
                    $so_detail->sod_qty_ship = 0;
                    $so_detail->save();
    
                    $coddet = CustomerOrderDetail::lockForUpdate()->findOrFail($request->idcodetail[$key]);
                    if($coddet->cod_qty_used + $request->qtyord[$key] > $coddet->cod_qty_ord){
                        alert()->error('Error', 'Save Gagal, Qty Customer Order berubah')->persistent('Dismiss');
                        return back();
                    }
                    $coddet->cod_qty_used = $coddet->cod_qty_used + $request->qtyord[$key];
                    $coddet->save();
                }
            }

            $prefix->prefix_so_rn = substr($getrn,2,6);
            $prefix->save();

            $comstr = CustomerOrderMstr::find($request->conbr);
            if($comstr->co_status == 'New'){
                $comstr->co_status = 'Ongoing';
            }
            $comstr->save();

            DB::commit();
            alert()->success('Success', 'Sales Order : '.$getrn.' Created')->persistent('Dismiss');
            return back();
            

        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Failed to create SO')->persistent('Dismiss');
            return back();
        }
    }

    public function edit(SalesOrderMstr $salesOrderMstr, $id)
    {
        $data = SalesOrderMstr::with('getDetail.getItem','getCOMaster.getCustomer','getCOMaster.getDetail','getNonCancelledSJ','getShipFrom','getShipTo')->findOrFail($id);
        
        $item = CustomerOrderDetail::with('getItem')->where('cod_co_mstr_id',$data->so_co_mstr_id)->get();

        $shipfrom = ShipFrom::get();

        $shipto = CustomerShipTo::where('cs_cust_code',$data->getCOMaster->co_cust_code)->orderBy('cs_shipto','asc')->get();

        $this->authorize('update',[SalesOrderMstr::class, $data]);
        
        return view('transaksi.salesorder.edit',compact('data','item','shipfrom','shipto'));
    }

    public function update(Request $request, SalesOrderMstr $salesOrderMstr)
    {
        $id = $request->idmaster;
        $duedate = $request->duedate;
        $shipfrom = $request->shipfrom;
        $shipto = $request->shipto;
        $remark = $request->remark;

        $operation = $request->operation;
        $iddetail = $request->iddetail;
        $line = $request->line;
        $part = $request->part;
        $qtyord = $request->qtyord;
        $qtyold = $request->qtyold;
        $qtyship = $request->qtyship;
        $um = $request->um;

        DB::beginTransaction();
        try{
            $master = SalesOrderMstr::findOrFail($id);
            $this->authorize('update',[SalesOrderMstr::class, $master]);

            $master->so_due_date = $duedate;
            $master->so_ship_from = $shipfrom;
            $master->so_ship_to = $shipto;
            $master->so_remark = $remark;
            $master->save();

            foreach($iddetail as $key => $details){
                $detail = SalesOrderDetail::lockForUpdate()->firstOrNew(['id' => $details]);
                if($operation[$key] == 'R'){
                    $detail->delete();
                }else{
                    $detail->sod_so_mstr_id = $id;
                    $detail->sod_line = $line[$key];
                    $detail->sod_part = $part[$key];
                    $detail->sod_qty_ord = $qtyord[$key];
                    $detail->sod_qty_ship = $qtyship[$key];
                    $detail->sod_um = $um[$key];
                    $detail->save();
                }

                $codetail = CustomerOrderDetail::query()
                                    ->where('cod_co_mstr_id',$master->so_co_mstr_id)
                                    ->where('cod_line',$line[$key])
                                    ->where('cod_part',$part[$key])
                                    ->lockForUpdate()
                                    ->first();
                if($request->operation[$key] == 'R'){        
                    $codetail->cod_qty_used = $codetail->cod_qty_used - $request->qtyold[$key];
                    $codetail->save();
                }else{
                    $codetail->cod_qty_used = $codetail->cod_qty_used - $qtyold[$key] + $qtyord[$key];
                    if($codetail->cod_qty_used > $codetail->cod_qty_ord){
                        DB::rollback();
                        alert()->error('Error', 'Data Sudah berubah, silahkan dicoba lagi')->persistent('Dismiss');
                        return back();
                    }else{
                        $codetail->save();
                    }
                }         
                
            }

            DB::commit();
            alert()->success('Success', 'SO Updated')->persistent('Dismiss');
            return back();
        }catch(\Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Failed to update so')->persistent('Dismiss');
            return back();
        }

    }

    public function destroy(SalesOrderMstr $salesOrderMstr, Request $request)
    {
        $id = $request->temp_id;

        DB::beginTransaction();

        try{
            $somstr = SalesOrderMstr::with('getDetail')->findOrFail($id);
            
            $this->authorize('delete',[SalesOrderMstr::class, $somstr]);
            
            $soddet = SalesOrderDetail::where('sod_so_mstr_id',$id)->get();
            foreach($soddet as $key => $soddets){
                $coddet = CustomerOrderDetail::query()
                                    ->where('cod_line',$soddets->sod_line)
                                    ->where('cod_part',$soddets->sod_part)
                                    ->where('cod_co_mstr_id',$somstr->so_co_mstr_id)
                                    ->firstOrFail();
                $coddet->cod_qty_used = $coddet->cod_qty_used - $soddets->sod_qty_ord;
                $coddet->save();
            }
            
            $somstr->so_status = 'Cancelled';
            $somstr->save();
            
            DB::commit();
            alert()->success('Success', 'Sales Order Deleted Successfully')->persistent('Dismiss');
        }catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to delete Sales Order')->persistent('Dismiss');
        }

        return redirect()->route('salesorder.index');
    }

    public function getdetail($id)
    {
        $data = SalesOrderDetail::with('getItem')->where('sod_so_mstr_id',$id)->get();
        $output = '<tr><td colspan="7"> No Data Avail </td></tr>';
        if($data->count() > 0){
            $output = '';
            foreach($data as $datas){
                $output .= '<tr>';
                $output .= '<td>'.$datas->sod_line.'</td>';
                $output .= '<td>'.$datas->sod_part.' - '.$datas->getItem->item_desc .'</td>';
                $output .= '<td>'.$datas->sod_um.'</td>';
                $output .= '<td>'.$datas->sod_qty_ord.'</td>';
                $output .= '<td>'.$datas->sod_qty_ship.'</td>';
                $output .= '</tr>';
            }
        }

        return response($output);
    }

    public function getum(Request $request)
    {
        $item = Item::where('item_part',$request->search)->firstOrFail();
        $um = $item->item_um ?? '';

        return $um;
    }

    public function getshipto(Request $request)
    {
        $output = '';

        $shipto = CustomerShipTo::where('cs_cust_code',$request->search)->orderBy('cs_shipto','asc')->get();
        if($shipto->count() > 0){
            foreach($shipto as $data){
                $output .= '<option value="'.$data->cs_shipto.'">'.$data->cs_shipto.' - '.$data->cs_shipto_name.'</option>';
            }
        }

        return $output;
    }

    public function getco(Request $request)
    {
        $coddetail = CustomerOrderDetail::query()
                                ->with('getItem')
                                ->where('cod_co_mstr_id',$request->search)
                                ->get();
        $output = '';
        if($coddetail->count() > 0){
            foreach($coddetail as $datas){
                $qtyopen = $datas->cod_qty_ord - $datas->cod_qty_used;
                $output .= '<tr>';
                $output .= '<td>'.$datas->cod_line.'<input type="hidden" name="line[]" value="'.$datas->cod_line.'"></td>';
                $output .= '<td>'.$datas->cod_part.' - '.$datas->getItem->item_desc.'<input type="hidden" name="part[]" value="'.$datas->cod_part.'"></td>';
                $output .= '<td>'.$datas->getItem->item_um.'<input type="hidden" name="um[]" value="'.$datas->getItem->item_um.'"></td>';
                $output .= '<td>'.(int)$datas->cod_qty_ord.'</td>';
                $output .= '<td>'.$qtyopen.'</td>';
                if($qtyopen == 0){
                    $output .= '<td>
                                <input type="hidden" name="idcodetail[]" value="'.$datas->id.'">
                                <input type="number" class="form-control" name="qtyord[]" max="'.$qtyopen.'" required disabled>
                                </td>';
                }else{
                    $output .= '<td>
                    <input type="hidden" name="idcodetail[]" value="'.$datas->id.'">
                    <input type="number" class="form-control" name="qtyord[]" max="'.$qtyopen.'" required>
                    </td>';
                }
                $output .= '</tr>';
            }
        }

        return $output;
    }

    public function getalokasiso($id)
    {
        $data = SalesOrderDetail::with('getItem')->where('sod_so_mstr_id',$id)->get();
        $output = '<tr><td colspan="7"> No Data Avail </td></tr>';
        if($data->count() > 0){
            $output = '';
            foreach($data as $datas){
                $output .= '<tr>';
                $output .= '<td>'.$datas->sod_line.'</td>';
                $output .= '<td>'.$datas->sod_part.' - '.$datas->getItem->item_desc .'</td>';
                $output .= '<td>'.$datas->getItem->item_um.'</td>';
                $output .= '<td>'.(int)$datas->sod_qty_ord.'</td>';
                $output .= '<td>'.(int)$datas->sod_qty_ship.'</td>';
                $output .= '</tr>';

                $list = SuratJalan::query()
                                ->with('getDetail','getTruck')
                                ->where('sj_so_mstr_id',$datas->sod_so_mstr_id)
                                ->whereRelation('getDetail','sjd_line',$datas->sod_line)
                                ->whereRelation('getDetail','sjd_part',$datas->sod_part)
                                ->where('sj_status','!=','Cancelled')
                                ->orderBy('sj_surat_jalan','ASC')
                                ->get();
                                // ->sortByDesc('getTruck.truck_no_polis');
                

                if($list->count() > 0){
                    $value = 0;
                    foreach($list as $key => $lists){
                        $value += 1;
                        $output .= '<tr>';
                        $output .= '<td colspan="2"><b>SPK Number : '.$lists->sj_nbr.', SJ Number : '.$lists->sj_surat_jalan.'</b></td>';
                        $output .= '<td><b>Nopol : '.$lists->getTruck->truck_no_polis.'</b></td>';
                        $output .= '<td><b> Status : '.$lists->sj_status.'</b></td>';
                        foreach($lists->getDetail as $detail){
                            if($detail->sjd_part == $datas->sod_part){
                                $output .= '<td><b>Qty : '.(int)$detail->sjd_qty_ship.'</b></td>';
                            }
                        }
                        $output .= '</tr>';
                    }
                    $output .= '<tr><td colspan="5"><b>Total SPK : '.$value.'</b></td></tr>';
                }
            }
        }

        return $output;
    }
}
