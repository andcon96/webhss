<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SalesOrderSangu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesOrderSanguController extends Controller
{
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

        return view('transaksi.sangu.index',compact('data','cust'));
    }

    public function show(Request $request, $id){
        $data = SalesOrderMstr::with(['getDetail','getSangu.getTruckDriver.getUser'])->findOrFail($id);
        
        $this->authorize('view', [SalesOrderMstr::class, $data]);
        
        return view('transaksi.sangu.show',compact('data'));
    }

    public function edit(SalesOrderSangu $salesOrderSangu, $id)
    {
        $data = SalesOrderMstr::with(['getDetail','getSangu'])->where('id',$id)->firstOrFail();

        $this->authorize('update',[SalesOrderMstr::class, $data]);
        
        $truck = TruckDriver::with(['getUser'])->where('truck_is_active',1)->orderBy('truck_no_polis','ASC')->get();

        return view('transaksi.sangu.edit',compact('data','truck'));
    }

    public function update(Request $request, SalesOrderSangu $salesOrderSangu)
    {
        DB::beginTransaction();
        try{
            foreach($request->iddetail as $key => $datas){
                $sangu = SalesOrderSangu::firstOrNew(['id' => $datas]);
                if($request->operation[$key] == "R"){
                    $sangu->delete();
                }else{
                    $sangu->sos_so_mstr_id = $request->idmaster;
                    $sangu->sos_truck = $request->polis[$key];
                    $sangu->sos_tot_trip = $request->qtytrip[$key];
                    $sangu->sos_sangu = str_replace(',','',$request->totsangu[$key]);
                    $sangu->save();
                }
            }
            
            $so_mstr = SalesOrderMstr::findOrFail($request->idmaster);
            $so_mstr->so_status = "Open";
            $so_mstr->save();

            DB::commit();
            alert()->success('Success', 'Alokasi Sangu Created')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Failed to create Alokasi Sangu')->persistent('Dismiss');
            return back();
        }
        
        
    }

}
