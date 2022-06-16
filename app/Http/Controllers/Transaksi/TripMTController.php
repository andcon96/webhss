<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use App\Models\Transaksi\SalesOrderSangu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripMTController extends Controller
{
    public function index(Request $request){
        $truck = Truck::orderBy('truck_no_polis','ASC')->get();

        $truckDriver = TruckDriver::where('truck_no_polis',$request->truck)->where('truck_is_active',1)->first();
        $selectTruck = $truckDriver->id ?? 0;

        $data = SalesOrderSangu::query()->with(['getTruckDriver.getTruck','getMaster'])
                            ->with('countLaporanHist', function ($query) use($selectTruck) {
                                $query->where('soh_driver','=',$selectTruck);
                            });

        $user = Auth::user()->id;
        $truckUser = TruckDriver::with('getTruck')->where('truck_user_id', $user)->where('truck_is_active', 1)->first();

        if($request->truck){
            $data->whereRelation('getTruckDriver.getTruck','id',$request->truck);
            $data = $data->orderBy('created_at','DESC')->take(5)->get();
        }else{
            if ($truckUser) {
                $data = SalesOrderSangu::with(['getMaster','getTruckDriver.getTruck'])
                                        ->with('countLaporanHist', function ($query) use($truckUser) {
                                            $query->where('soh_driver','=',$truckUser->id);
                                        })
                                       ->whereRelation('getTruckDriver.getTruck','id',$truckUser->getTruck->id)
                                       ->orderBy('created_at','DESC')
                                       ->get();
            } else {
                $data = [];
            }
        }

        return view('transaksi.trip.index',compact('truck','data','truckUser'));
    }
}
