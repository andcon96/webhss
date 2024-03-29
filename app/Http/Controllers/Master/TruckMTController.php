<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\SubDomain;
use App\Models\Master\TipeTruck;
use App\Models\Master\Truck;
use App\Models\Master\User;
use App\Services\QxtendServices;
use App\Services\WSAServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TruckMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Truck::query()->withoutGlobalScopes()->with('getTipe','getUserDriver','getUserPengurus');
        $truck = Truck::withoutGlobalScopes()->get();
        $tipe = TipeTruck::get();
        $user = User::get();
        $subdomain = SubDomain::get();

        if($request->s_truck){
            $data->where('id',$request->s_truck);
        }

        if($request->s_tipe){
            $data->where('truck_tipe_id',$request->s_tipe);
        }
        $domain = Domain::groupBy('domain_code')->get();
        $data = $data->orderBy('truck_domain')->orderBy('truck_no_polis')->paginate(10);

        return view('setting.truck.index',['data' => $data, 'user' => $user, 'truck' => $truck, 'tipe' => $tipe,'domain' => $domain, 'subdomain' => $subdomain]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $newTruck = new Truck();
        $newTruck->truck_domain = $request->domain;
        
        $newTruck->truck_no_polis = $request->polis;
        $newTruck->truck_user_id = $request->driver ?? null;
        $newTruck->truck_pengurus_id = $request->pengurus ?? null;
        $newTruck->truck_tipe_id = $request->tipetruck;
        $newTruck->truck_sub_domain = $request->subdom;
        $newTruck->save();

        
        alert()->success('Success', 'Truck successfully created');
        return redirect()->route('truckmaint.index');
    }

    public function edit($id){
        $data = Truck::withoutGlobalScopes()->with('getUserDriver','getUserPengurus')->findOrFail($id);
        $user = User::get();
        $tipetruck = TipeTruck::get();
        $domain = Domain::groupby('domain_code')->get();
        $subdomain = SubDomain::get();

        return view('setting.truck.edit',['data' => $data, 'user' => $user, 'domain' => $domain,'tipetruck' => $tipetruck, 'subdomain' => $subdomain]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Truck  $truck
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->isactive == 1){
            $driver = $request->driver;
            $pengurus = $request->pengurus;
            $domain = $request->domain;
            $subdomain = $request->subdomain;
            DB::beginTransaction();
    
            try{
                
                $truck = Truck::findOrFail($id);
                $truck->truck_domain = $domain;
                $truck->truck_no_polis = $request->nopol;
                $truck->truck_user_id = $driver;
                $truck->truck_pengurus_id = $pengurus;
                $truck->truck_tipe_id = $request->tipetruck;
                $truck->truck_sub_domain = $request->subdomain;
                if ($truck->isDirty()) {
                    $truck->save();
                }
    
                DB::commit();
                alert()->success('Success', 'Truck updated successfully');
            }catch (Exception $err) {
                DB::rollBack();
                alert()->error('Error', 'Failed to update truck');
            }
    
        }
        else if($request->isactive == 0){
            
            DB::beginTransaction();
            try{
                $truck = Truck::where('id',$id)->first();
                $truck->new_truck_note = $request->newnopol;
                $truck->save();
                DB::commit();
                alert()->success('Success', 'Truck updated successfully');
            }catch (Exception $err) {
                DB::rollBack();
                alert()->error('Error', 'Failed to update truck');
            }
        }

        return redirect()->to($request->prevurl ?? route('truckmaint.index'));

        // return redirect()->route('truckmaint.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Truck  $truck
     * @return \Illuminate\Http\Response
     */
    public function destroy(Truck $truck, Request $request)
    {
        //
        $id = $request->temp_id;

        DB::beginTransaction();

        try{
            $truck = Truck::where('id', $id)->firstOrFail();

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

        return redirect()->route('truckmaint.index');
    }

    public function loadnopol(){
        $truck = Truck::get();
        DB::beginTransaction();
        try {
            foreach($truck as $tr){
                $checkwo = (new WSAServices())->wsaloadtruck($tr->truck_no_polis);
                if($checkwo != false){
                    $newnopol = strval($checkwo[0]);
                    Truck::where('truck_no_polis',$tr->truck_no_polis)->update(['truck_no_polis' => $newnopol]); 
                }

            }
            DB::commit();
        }catch(Exception $err){
            DB::rollback();
            dd('failed');
        }
    }
    public function LoadNopolExcelToQad(){
        $arrayrusakdet = [];
        $arraytruckilang = [];
        ini_set('max_execution_time', 360);

        if (($open = fopen(public_path() . "/truckexcel.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                $kr[] = $data;
            }

            $tipetruck = ''; 
            DB::beginTransaction();
            try{
                
                $loadnopol = (new QxtendServices())->qxLoadNopol('TRUCK',$kr);
                
                // KerusakanDetail::insert($arrayrusakdet);
                $arrayrusakdet = [];
                
                DB::commit();
                fclose($open);
                alert()->Success('success', 'data successfully inserted')->persistent('Dismiss');
                return back();
            }
            catch(Exception $err){
                DB::rollBack();
                dd($err);
                alert()->error('Error', 'Failed to submit data')->persistent('Dismiss');
                return back();
            }

        }
        
    }
}
