<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Domain;
use App\Models\Master\Item;
use App\Models\Master\Prefix;
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
        $data = SalesOrderMstr::query();
        if($request->s_sonumber){
            $data->where('so_nbr',$request->s_sonumber);
        }
        if($request->s_customer){
            $data->where('so_cust',$request->s_customer);
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


        $data = $data->with('getDetail')->orderBy('created_at','DESC')->paginate(10);
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
            Prefix::lockForUpdate()->first();

            $getrn = (new CreateTempTable())->getrnso();
            if($getrn === false){
                alert()->error('Error', 'Failed to create SO')->persistent('Dismiss');
                DB::rollBack();
                return back();
            }

            $sendSO = (new QxtendServices())->qxSOMaintenance($request->all(),$getrn);
            if($sendSO === false){
                alert()->error('Error', 'Error Qxtend, Silahkan cek URL Qxtend.')->persistent('Dismiss');
                return back();
            }elseif($sendSO == 'nourl'){
                alert()->error('Error', 'Mohon isi URL Qxtend di Setting QXWSA.')->persistent('Dismiss');
                return back();
            }elseif($sendSO[0] == 'error'){
                alert()->error('Error', 'Qxtend kembalikan error, Silahkan cek log Qxtend')->persistent('Dismiss');
                return back();
            }
            
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
        $data = SalesOrderMstr::with('getDetail.getItem')->findOrFail($id);

        $this->authorize('update',[SalesOrderMstr::class, $data]);
        
        $item = Item::get();
        return view('transaksi.salesorder.edit',compact('data','item'));
    }

    public function update(Request $request, SalesOrderMstr $salesOrderMstr)
    {
        // dd($request->all());
        $id = $request->idmaster;
        $shipfrom = $request->shipfrom;
        $shipto = $request->shipto;
        $duedate = $request->duedate;

        $operation = $request->operation;
        $iddetail = $request->iddetail;
        $line = $request->line;
        $part = $request->part;
        $qtyord = $request->qtyord;
        $qtyship = $request->qtyship;
        $um = $request->um;

        DB::beginTransaction();
        try{
            $master = SalesOrderMstr::findOrFail($id);
            $master->so_ship_from = $shipfrom;
            $master->so_ship_to = $shipto;
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
            $truck = SalesOrderMstr::where('id', $id)->firstOrFail();
            $truck->so_status = 'Cancelled';
            $truck->save();

            DB::commit();
            alert()->success('Success', 'Sales Order Deleted Successfully')->persistent('Dismiss');
        }catch (\Exception $err) {
            DB::rollBack();
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
