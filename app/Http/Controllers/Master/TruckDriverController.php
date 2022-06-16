<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use App\Models\Master\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TruckDriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TruckDriver::query();
        $truck = Truck::where('truck_is_active',1)->get();
        $user = User::get();

        if($request->s_truck){
            $data->where('truck_no_polis',$request->s_truck);
        }

        $data = $data->with(['getUser','getTruck'])->orderBy('truck_is_active','DESC')->paginate(10);

        return view('setting.truckdriver.index',['data' => $data, 'user' => $user, 'truck' => $truck]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cektruck = TruckDriver::where('truck_no_polis',$request->polis)->first();
        if($cektruck){
            alert()->error('Error', 'Truck Sudah Terdaftar, Non Aktifkan yang lama jika ingin lanjut');
            return redirect()->route('truckdrivemaint.index');
        }
        $newTruck = new TruckDriver();
        $newTruck->truck_no_polis = $request->polis;
        $newTruck->truck_user_id = $request->driver;
        $newTruck->save();

        
        alert()->success('Success', 'Truck successfully created');
        return redirect()->route('truckdrivemaint.index');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Truck  $truck
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TruckDriver $truck)
    {
        // dd($request->all());
        $id = $request->e_id;
        $polis = $request->e_polis;
        $userid = $request->e_driver;

        DB::beginTransaction();

        try{
            $truck = TruckDriver::where('id', $id)->firstOrFail();
            $truck->truck_user_id = $userid;
            if ($truck->isDirty()) {
                $truck->save();
            }

            DB::commit();
            alert()->success('Success', 'Truck updated successfully');
        }catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to update truck');
        }

        return redirect()->route('truckdrivemaint.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Truck  $truck
     * @return \Illuminate\Http\Response
     */
    public function destroy(TruckDriver $truck, Request $request)
    {
        // dd($request->all());
        $id = $request->temp_id;

        DB::beginTransaction();

        try{
            $truck = TruckDriver::where('id', $id)->firstOrFail();
            $totalTruck = TruckDriver::where('truck_no_polis',$truck->truck_no_polis)
                                     ->where('truck_is_active',1)->count();
            if($totalTruck > 0 && $truck->truck_is_active == 0){
                alert()->error('Error', 'Tidak boleh ada No Polis yang sama yang aktif, Silahkan non aktifkan yang lama');
                return redirect()->route('truckdrivemaint.index');
            }
                              
            $newstat = 0;
            $truck->truck_is_active == 0 ? $newstat = 1 : $newstat = 0;
            $truck->truck_is_active = $newstat;
            $truck->save();
            // $truck->delete();

            DB::commit();
            alert()->success('Success', 'Truck Deactivated Successfully');
        }catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to delete truck');
        }

        return redirect()->route('truckdrivemaint.index');
    }
}
