<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
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

        $userDriver = Truck::where('truck_user_id',$user)->first();

        $truck = Truck::get();

        $data = Truck::query()->with('getUserDriver','getLastCheckInOut');

        if($userDriver){
            $data->where('id',$userDriver->id);
        }else{
            if($request->polis){
                $data->where('id',$request->polis);
            }
        }

        $data = $data->orderBy('truck_no_polis','asc')->paginate(10);
        return view('transaksi.checkinout.index',compact('data','userDriver','truck'));
    }
    

    public function show(Request $request, $id)
    {
        $data = Truck::with(['getAllCheckInOut'])->findOrFail($id);

        $detail = $data->getAllCheckInOut()->orderBy('created_at','desc')->paginate(10);

        // dd($detail);

        return view('transaksi.checkinout.show',compact('data','detail'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $lastaction = CheckInOut::where('cio_truck',$request->truck)->orderBy('created_at','DESC')->first();
            if($lastaction){
                $newaction = $lastaction->cio_is_check_in == 1 ? 0 : 1;
            }else{
                $newaction = 1;
            }

            $datacheck = new CheckInOut();
            $datacheck->cio_truck = $request->truck;
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
