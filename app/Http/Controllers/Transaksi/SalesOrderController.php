<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Domain;
use App\Models\Master\Item;
use App\Models\Master\Prefix;
use App\Models\Transaksi\CustomerOrderDetail;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SalesOrderDetail;
use App\Services\CreateTempTable;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cust = Customer::get();
        $data = SalesOrderMstr::query()
                              ->with('getDetail',
                                     'getCOMaster.getCustomer');

        if($request->s_sonumber){
            $data->where('so_nbr',$request->s_sonumber);
        }
        if($request->s_customer){
            $data->whereRelation('getCOMaster.getCustomer','co_cust_code',$request->s_customer);
        }
        if($request->s_shipto){
            $data->where('so_ship_to',$request->s_shipto);
        }
        if($request->s_shipfrom){
            $data->where('so_ship_from',$request->s_shipfrom);
        }
        if($request->s_status){
            $data->where('so_status',$request->s_status);
        }


        $data = $data->orderBy('created_at','DESC')->paginate(10);
        return view('transaksi.salesorder.index',['data' => $data, 'cust' => $cust]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = Item::get();
        $cust = Customer::get();
        return view('transaksi.salesorder.create',compact('item','cust'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

            // $sendSO = (new QxtendServices())->qxSOMaintenance($request->all(),$getrn);
            // if($sendSO === false){
            //     alert()->error('Error', 'Error Qxtend, Silahkan cek URL Qxtend.')->persistent('Dismiss');
            //     return back();
            // }elseif($sendSO == 'nourl'){
            //     alert()->error('Error', 'Mohon isi URL Qxtend di Setting QXWSA.')->persistent('Dismiss');
            //     return back();
            // }elseif($sendSO[0] == 'error'){
            //     alert()->error('Error', 'Qxtend kembalikan error, Silahkan cek log Qxtend')->persistent('Dismiss');
            //     return back();
            // }
            
            $so_mstr = new SalesOrderMstr();
            $so_mstr->so_nbr = $getrn;
            $so_mstr->so_cust = $request->customer;
            $so_mstr->so_type = $request->type;
            $so_mstr->so_ship_from = $request->shipfrom;
            $so_mstr->so_ship_to = $request->shipto;
            $so_mstr->so_due_date = $request->duedate;
            $so_mstr->so_domain = $request->domains;
            $so_mstr->save();

            $id = $so_mstr->id;
            foreach($request->line as $key => $datas){
                $so_detail = new SalesOrderDetail();
                $so_detail->sod_so_mstr_id = $id;
                $so_detail->sod_line = $datas;
                $so_detail->sod_part = $request->part[$key];
                $so_detail->sod_um = $request->um[$key];
                $so_detail->sod_qty_ord = $request->qtyord[$key];
                $so_detail->sod_qty_ship = 0;
                $so_detail->save();
            }

            // $prefix = Prefix::firstOrFail();
            $prefix = Domain::where('domain_code',Session::get('domain'))->firstOrFail();
            $prefix->domain_so_rn = substr($getrn,2,6);
            $prefix->save();

            DB::commit();
            alert()->success('Success', 'Sales Order Created')->persistent('Dismiss');
            return back();
            

        }catch(Exception $e){
            DB::rollback();
            dd($e);
            alert()->error('Error', 'Failed to create SO')->persistent('Dismiss');
            return back();
        }
    }

    public function edit(SalesOrderMstr $salesOrderMstr, $id)
    {
        $data = SalesOrderMstr::with('getDetail.getItem','getCOMaster.getCustomer','getCOMaster.getDetail')->findOrFail($id);
        
        $item = CustomerOrderDetail::with('getItem')->where('cod_co_mstr_id',$data->so_co_mstr_id)->get();

        $this->authorize('update',[SalesOrderMstr::class, $data]);
        
        return view('transaksi.salesorder.edit',compact('data','item'));
    }

    public function update(Request $request, SalesOrderMstr $salesOrderMstr)
    {
        $id = $request->idmaster;
        $duedate = $request->duedate;

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
            $master->so_due_date = $duedate;
            $master->save();

            foreach($iddetail as $key => $details){
                $detail = SalesOrderDetail::firstOrNew(['id' => $details]);
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

                if($qtyold[$key] != $qtyord[$key]){
                    $codetail = CustomerOrderDetail::query()
                                        ->where('cod_co_mstr_id',$master->so_co_mstr_id)
                                        ->where('cod_line',$line[$key])
                                        ->where('cod_part',$part[$key])
                                        ->first();
                                        
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi\SalesOrderMstr  $salesOrderMstr
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesOrderMstr $salesOrderMstr, Request $request)
    {
        // dd($request->all());
        $id = $request->temp_id;

        DB::beginTransaction();

        try{
            $somstr = SalesOrderMstr::findOrFail($id);
            
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
            dd($err);
            alert()->error('Error', 'Failed to delete Sales Order')->persistent('Dismiss');
        }

        return redirect()->route('salesorder.index');
    }


    public function getdetail($id){
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

    public function getum(Request $request){
        $item = Item::where('item_part',$request->search)->firstOrFail();
        $um = $item->item_um ?? '';

        return $um;
    }

    public function getshipto(Request $request){
        $output = '';

        $customer = Customer::where('cust_code',$request->search)->firstOrFail();
        $output .= '<option value="'.$customer->cust_code.'">'.$customer->cust_code.'</option>';

        $shipto = CustomerShipTo::where('cust_code',$request->search)->get();
        if($shipto->count() > 0){
            foreach($shipto as $data){
                $output .= '<option value="'.$data->cust_shipto.'">'.$data->cust_shipto.'</option>';
            }
        }

        return $output;
    }
}
