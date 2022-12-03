<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
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
                               'getSOMaster.getShipFrom',
                               'getSOMaster.getShipTo',
                               'getHistTrip');

        $userid = Auth::user()->id;
        $userDriver = Truck::where('truck_user_id',$userid)->first();
        
        
        if($request->truck){
            $data->whereRelation('getTruck','id',$request->truck);
        }

        if($request->spk){
            $data->where('sj_nbr',$request->spk);
        }

        if($request->status){
            $data->where('sj_status',$request->status);
        }
        
        if ($userDriver) {
            $data = SuratJalan::query()
                              ->with(['getSOMaster','getTruck',
                                    'getSOMaster.getShipFrom',
                                    'getSOMaster.getShipTo',])
                              ->with('getHistTrip', function($query) use($userDriver){
                                    $query->where('sjh_truck',$userDriver->id);
                              })
                              ->where('sj_truck_id',$userDriver->id)
                              ->orderBy('created_at','DESC')
                              ->paginate(10);
                              
        } else {
            $data = $data->paginate(10);
        }
        
        return view('transaksi.trip.index',compact('truck','data','userDriver'));
    }
}
