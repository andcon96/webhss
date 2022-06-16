<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use App\Models\Transaksi\CheckInOut;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckInOutController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user()->id;
        $truckDriver = TruckDriver::with('getTruck')
                                ->where('truck_user_id',$user)
                                ->where('truck_is_active',1)
                                ->first();
        $truck = Truck::get();
        
        // $data = CheckInOut::query();
        // if($truckDriver){
        //     $data->where('cio_truck_driver',$truckDriver->id);
        // }else{
        //     if($request->polis){
        //         $search = TruckDriver::with('getTruck')
        //                             ->whereRelation('getTruck','id','=',$request->polis)
        //                             ->where('truck_is_active',1)
        //                             ->first();
        //         $data->where('cio_truck_driver',$search->id ?? 0);
        //     }
        // }

        // $data = $data->with('getTruckDriver.getTruck','getTruckDriver.getUser')->orderBy('created_at','DESC')->paginate(10);

        $data = Truck::query();

        if($truckDriver){
            $data->whereRelation('getTruckDriver.getLastCheckInOut',
                                'cio_truck_driver',$truckDriver->id);
        }else{
            if($request->polis){
                $search = TruckDriver::with('getTruck')
                                    ->whereRelation('getTruck','id','=',$request->polis)
                                    ->where('truck_is_active',1)
                                    ->first();
                $data->whereRelation('getTruckDriver.getLastCheckInOut',
                                'cio_truck_driver',$search->id ?? 0);
            }
        }

        $data = $data->with('getActiveDriver.getUser','getActiveDriver.getLastCheckInOut')->get();

        // dd($data);

        return view('transaksi.checkinout.index',compact('data','truckDriver','truck'));
    }
    

    public function show(Request $request, $id)
    {
        $data = Truck::with('getActiveDriver.getAllCheckInOut')->findOrFail($id);

        return view('transaksi.checkinout.show',compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $lastaction = CheckInOut::where('cio_truck_driver',$request->truckdriver)->orderBy('created_at','DESC')->first();
            if($lastaction){
                $newaction = $lastaction->cio_is_check_in == 1 ? 0 : 1;
            }else{
                $newaction = 1;
            }

            $datacheck = new CheckInOut();
            $datacheck->cio_truck_driver = $request->truckdriver;
            $datacheck->cio_is_check_in = $newaction;
            $datacheck->save();

            DB::commit();
            alert()->success('Success', 'Check IN / OUT Saved')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Failed to Check IN / OUT')->persistent('Dismiss');
            return back();
        }
    }
}
