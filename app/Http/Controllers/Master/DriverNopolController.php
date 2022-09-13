<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Driver;
use App\Models\Master\DriverNopol;
use App\Models\Master\Truck;
use Illuminate\Http\Request;

class DriverNopolController extends Controller
{
    public function index(Request $request)
    {
        $data = DriverNopol::query()->with(['getTruck','getDriver']);

        if($request->driver){
            $data->whereRelation('getDriver','id',$request->driver);
        }

        if($request->truck){
            $data->whereRelation('getTruck','id',$request->truck);
        }

        $data = $data->paginate(10);

        $listdriver = Driver::get();
        $listtruck = Truck::get();

        $listactivedriver = Driver::where('driver_is_active',1)->get();
        $listactivetruck = Truck::where('truck_is_active',1)->get();

        return view('setting.drivernopol.index',compact('data','listdriver','listtruck','listactivedriver','listactivetruck'));
    }

    public function store(Request $request)
    {
        $link = new DriverNopol();
        $link->dn_driver_id = $request->driver;
        $link->dn_truck_id = $request->truck;
        $link->save();

        alert()->success('Success','Link Driver Nopol Created');
        return back();
    }

    public function update(Request $request)
    {
        $link = DriverNopol::findOrFail($request->id);
        $link->dn_is_active = $link->dn_is_active == 1 ? 0 : 1;
        $link->save();
        
        alert()->success('Success','Link Driver Updated');
        return back();
    }

}
