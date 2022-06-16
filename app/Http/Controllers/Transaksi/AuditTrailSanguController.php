<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\TruckDriver;
use App\Models\Transaksi\SalesOrderMstr;
use Illuminate\Http\Request;
use App\Models\Transaksi\SalesOrderSangu;

class AuditTrailSanguController extends Controller
{
    public function index(Request $request){
        $truckdriver = TruckDriver::query()
                            ->with([
                                'getUser',
                                'getTruck'
                            ])
                            ->get();
        
        $listso = SalesOrderMstr::get();
        
        $listcust = Customer::get();

        $data = SalesOrderSangu::query()
                            ->with([
                                'getMaster.getCustomer',
                                'getTruckDriver.getTruck',
                                'countLaporanHist'
                            ]);

        if($request->idsonbr){
            $data->where('sos_so_mstr_id',$request->idsonbr);
        }
        if($request->custcode){
            $data->whereRelation('getMaster','so_cust',$request->custcode);
        }
        if($request->idtruckdriver){
            $data->where('sos_truck',$request->idtruckdriver);
        }

        $data = $data->orderBy('created_at','DESC')->paginate(10);

        return view('transaksi.sangu.audittrail.index',compact('data','listso','truckdriver','listcust'));
    }
}
