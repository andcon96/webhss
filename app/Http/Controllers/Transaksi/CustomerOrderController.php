<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Domain;
use App\Models\Master\Item;
use App\Models\Master\ShipFrom;
use App\Models\Transaksi\CustomerOrderDetail;
use App\Models\Transaksi\CustomerOrderMstr;
use App\Models\Transaksi\SalesOrderDetail;
use App\Models\Transaksi\SalesOrderMstr;
use App\Services\CreateTempTable;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $listcust = Customer::get();
        $listco = CustomerOrderMstr::get();
        $data = CustomerOrderMstr::query()
                                ->with('getCustomer');
        
        if($request->conumber){
            $data->where('id',$request->conumber);
        }

        if($request->s_customer){
            $data->where('co_cust_code',$request->s_customer);
        }

        $data = $data->paginate(10);
        
        return view('transaksi.customerorder.index',compact('data','listcust','listco'));
    }

    public function edit($id)
    {
        $data = CustomerOrderMstr::with('getDetail')->findOrFail($id);
        $this->authorize('update',[CustomerOrderMstr::class, $data]);
        $item = Item::where('item_promo',$data->co_type)->get();

        return view('transaksi.customerorder.edit',compact('data','item'));
    }

    public function create()
    {
        $item = Item::get();
        $cust = Customer::get();
        return view('transaksi.customerorder.create',compact('item','cust'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $getCORN = (new CreateTempTable())->getrnco();
            if($getCORN === false){
                alert()->error('Error', 'Save Gagal, Prefix belum disetup')->persistent('Dismiss');
                return back();
            }
            
            $comstr = new CustomerOrderMstr();
            $comstr->co_nbr = $getCORN;
            $comstr->co_cust_code = $request->customer;
            $comstr->co_type = $request->type;
            $comstr->co_status = "New";
            $comstr->co_remark = $request->remark;
            $comstr->save();

            $id = $comstr->id;
            foreach($request->line as $key => $datas){
                $coddet = new CustomerOrderDetail();
                $coddet->cod_co_mstr_id = $id;
                $coddet->cod_line = $datas;
                $coddet->cod_part = $request->part[$key];
                $coddet->cod_qty_ord = $request->qtyord[$key];
                $coddet->save();
            }

            $prefix = Domain::where('domain_code',Session::get('domain'))->firstOrFail();
            $prefix->domain_co_rn = substr($getCORN,2,6);
            $prefix->save();

            DB::commit();
            alert()->success('Success', 'Customer Order : '.$getCORN.'Created')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Save Gagal, Silahkan coba berberapa saat lagi')->persistent('Dismiss');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $data = CustomerOrderMstr::findOrFail($id);
        $this->authorize('update',[CustomerOrderMstr::class, $data]);
        DB::beginTransaction();
        try{

            foreach($request->iddetail as $key => $datas){
                if($request->operation[$key] == 'R' && $request->qtyord[$key] != $request->qtyopen[$key]){
                    DB::rollBack();
                    alert()->error('Error', 'Save Gagal, Line sudah dipakai, Tidak boleh didelete')->persistent('Dismiss');
                    return back();
                }

                $coddet = CustomerOrderDetail::firstOrNew(['id'=>$datas]);
                if($request->operation[$key] == 'R'){
                    $coddet->delete();
                }else{
                    $coddet->cod_co_mstr_id = $request->idmaster;
                    $coddet->cod_line = $request->line[$key];
                    $coddet->cod_part = $request->part[$key];
                    $coddet->cod_qty_ord = $request->qtynew[$key];
                    $coddet->save();
                }
            }


            alert()->success('Success', 'Save Berhasil')->persistent('Dismiss');
            DB::commit();
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Save Gagal')->persistent('Dismiss');
            return back();
        }
    }

    public function destroy(Request $request)
    {
        $comstr = CustomerOrderMstr::findOrFail($request->temp_id);
        if($comstr->co_status == 'New'){
            $comstr->co_status = 'Cancelled';
            $comstr->save();
            alert()->success('Success','Customer Order berhasil dicancel');
        }else{
            alert()->error('Error', 'Delete Gagal, Line sudah dipakai, Tidak boleh didelete')->persistent('Dismiss');
        }
        return back();
    }

    public function getdetail($id)
    {
        $data = CustomerOrderDetail::with('getItem')->where('cod_co_mstr_id',$id)->get();
        $output = '<tr><td colspan="7"> No Data Avail </td></tr>';
        if($data->count() > 0){
            $output = '';
            foreach($data as $datas){
                $output .= '<tr>';
                $output .= '<td>'.$datas->cod_line.'</td>';
                $output .= '<td>'.$datas->cod_part.' - '.$datas->getItem->item_desc .'</td>';
                $output .= '<td>EA</td>';
                $output .= '<td>'.$datas->cod_qty_ord.'</td>';
                $output .= '<td>'.$datas->cod_qty_used.'</td>';
                $output .= '</tr>';
            }
        }

        return response($output);

    }

    public function getalokasi($id)
    {
        $data = CustomerOrderDetail::with('getItem')->where('cod_co_mstr_id',$id)->get();
        $output = '<tr><td colspan="7"> No Data Avail </td></tr>';
        if($data->count() > 0){
            $output = '';
            foreach($data as $datas){
                $output .= '<tr>';
                $output .= '<td>'.$datas->cod_line.'</td>';
                $output .= '<td>'.$datas->cod_part.' - '.$datas->getItem->item_desc .'</td>';
                $output .= '<td>'.$datas->getItem->item_um.'</td>';
                $output .= '<td>'.$datas->cod_qty_ord.'</td>';
                $output .= '<td>'.$datas->cod_qty_used.'</td>';
                $output .= '</tr>';

                $list = SalesOrderMstr::query()
                                ->with('getDetail')
                                ->where('so_co_mstr_id',$datas->cod_co_mstr_id)
                                ->whereRelation('getDetail','sod_line',$datas->cod_line)
                                ->whereRelation('getDetail','sod_part',$datas->cod_part)
                                ->get();

                if($list->count() > 0){
                    foreach($list as $key => $lists){
                        $output .= '<tr>';
                        $output .= '<td colspan="2"><b>SO Number : '.$lists->so_nbr.'</b></td>';
                        $output .= '<td><b> Status : '.$lists->so_status.'</b></td>';
                        foreach($lists->getDetail as $detail){
                            if($detail->sod_part == $datas->cod_part){
                                $output .= '<td colspan="2"><b>Qty : '.$detail->sod_qty_ord.'</b></td>';
                            }
                        }
                        $output .= '</tr>';
                    }
                }
            }
        }

        return response($output);
    }

    public function createso($id)
    {
        $data = CustomerOrderMstr::with('getDetail.getItem')->findOrFail($id);
        $cust = Customer::get();
        $item = Item::get();
        $shipto = CustomerShipTo::where('cs_cust_code',$data->co_cust_code)->get();
        $shipfrom = ShipFrom::where('sf_is_active',1)->get();

        return view('transaksi.customerorder.salesorder.create', compact('data','item','cust','shipto','shipfrom'));
    }
    
    public function updateso(Request $request)
    {
        DB::beginTransaction();
        try{
            Domain::where('domain_code',Session::get('domain'))->lockForUpdate()->first();

            $getrn = (new CreateTempTable())->getrnso();
            if($getrn === false){
                alert()->error('Error', 'Failed to create SO')->persistent('Dismiss');
                DB::rollBack();
                return back();
            }
            
            $so_mstr = new SalesOrderMstr();
            $so_mstr->so_nbr = $getrn;
            $so_mstr->so_co_mstr_id = $request->idcomstr;
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

            $prefix = Domain::where('domain_code',Session::get('domain'))->firstOrFail();
            $prefix->domain_so_rn = substr($getrn,2,6);
            $prefix->save();

            $comstr = CustomerOrderMstr::find($request->idcomstr);
            if($comstr->co_status == 'New'){
                $comstr->co_status = 'Ongoing';
            }
            $comstr->save();
            
            

            $sendSO = (new QxtendServices())->qxSOMaintenance($request->all(),$getrn);
            if($sendSO === false){
                alert()->error('Error', 'Error Qxtend, Silahkan cek URL Qxtend.')->persistent('Dismiss');
                DB::rollback();
                return back();
            }elseif($sendSO == 'nourl'){
                alert()->error('Error', 'Mohon isi URL Qxtend di Setting QXWSA.')->persistent('Dismiss');
                DB::rollback();
                return back();
            }elseif($sendSO[0] == 'error'){
                alert()->error('Error', 'Qxtend kembalikan error, Silahkan cek log Qxtend')->persistent('Dismiss');
                DB::rollback();
                return back();
            }

            DB::commit();
            alert()->success('Success', 'Sales Order Created')->persistent('Dismiss');
            return back();
            

        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Failed to create SO')->persistent('Dismiss');
            return back();
        }
    }
}
