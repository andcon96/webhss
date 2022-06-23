<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use App\Models\Transaksi\SalesOrderSangu;
use App\Models\Transaksi\SuratJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripMTController extends Controller
{
    public function index(Request $request){
        $truck = Truck::orderBy('truck_no_polis','ASC')->get();

        $data = SuratJalan::query()
                        ->with('getTruck',
                               'getSOMaster.getCOMaster.getCustomer',
                               'getHistTrip');

        $userid = Auth::user()->id;
        $userDriver = Truck::where('truck_user_id',$userid)->first();
        
        
        if($request->truck){
            $data->whereRelation('getTruck','id',$request->truck);
            $data = $data->orderBy('created_at','DESC')->take(5)->get();
        }else{
            if ($userDriver) {
                $data = SuratJalan::query()
                                  ->with(['getSOMaster','getTruck'])
                                  ->with('getHistTrip', function($query) use($userDriver){
                                        $query->where('sjh_truck',$userDriver->id);
                                  })
                                  ->where('sj_truck_id',$userDriver->id)
                                  ->orderBy('created_at','DESC')
                                  ->get();
                                  
            } else {
                $data = [];
            }
        }
        
        return view('transaksi.trip.index',compact('truck','data','userDriver'));
    }
}
