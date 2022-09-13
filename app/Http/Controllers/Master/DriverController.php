<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listdriver = Driver::get();
        $driver = Driver::query();

        if($request->driver){
            $driver->where('id',$request->driver);
        }

        $driver = $driver->paginate(10);

        return view('setting.driver.index',compact('driver','listdriver'));
    }


    public function store(Request $request)
    {
        $driver = new Driver();
        $driver->driver_name = $request->namadriver;
        $driver->save();

        alert()->success('Success','Driver Created');
        return back();
    }

    public function update(Request $request, Driver $driver)
    {
        $driver = Driver::findOrFail($request->id);
        $driver->driver_is_active = $driver->driver_is_active == 1 ? 0 : 1;
        $driver->save();

        alert()->success('Success','Driver Updated');
        return back();
    }

}
