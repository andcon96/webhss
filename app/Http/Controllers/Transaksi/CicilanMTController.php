<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Driver;
use App\Models\Master\DriverNopol;
use App\Models\Master\Truck;
use App\Models\Transaksi\Cicilan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CicilanMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cicilan = Cicilan::query()->with(['getDriverNopol.getTruck','getDriverNopol.getDriver','getTotalPaid']);
        
        if($request->truck){
            $cicilan->whereRelation('getDriverNopol','dn_truck_id',$request->truck);
        }
        if($request->driver){
            $cicilan->whereRelation('getDriverNopol','dn_driver_id',$request->driver);
        }

        $cicilan = $cicilan->sortable()->paginate(10);

        $listtruck = Truck::get();
        $listdriver = Driver::get();
    
        return view('transaksi.cicilan.index',compact('cicilan','listtruck','listdriver'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $drivernopol = DriverNopol::with(['getTruck','getDriver'])->get();

        return view('transaksi.cicilan.create',compact('drivernopol'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $cicilan = new Cicilan();
            $cicilan->cicilan_dn_id = $request->drivernopol;
            $cicilan->cicilan_eff_date = $request->effdate;
            $cicilan->cicilan_remarks = $request->remark;
            $cicilan->cicilan_nominal = str_replace(',','',$request->nominal);
            $cicilan->save(); 

            DB::commit();
            alert()->success('Success', 'Data Saved')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Failed to save data')->persistent('Dismiss');
            return back();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi\Cicilan  $cicilan
     * @return \Illuminate\Http\Response
     */
    public function edit(Cicilan $cicilan, $id)
    {
        $cicilan = Cicilan::with(['getDriverNopol.getTruck','getDriverNopol.getDriver','getTotalPaid'])->findOrFail($id);
        $totalbayar = $cicilan->getTotalPaidActive->sum('hc_nominal') ?? 0 ; 
        
        return view('transaksi.cicilan.edit',compact('cicilan','totalbayar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi\Cicilan  $cicilan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cicilan $cicilan)
    {
        $nominal = str_replace(',','',$request->nominal);
        $totalbayar = str_replace(',','',$request->totpaid);
        
        if($nominal < $totalbayar){
            alert()->error('Error', 'Nominal lebih kecil dari Total Bayar')->persistent('Dismiss');
            return back();
        }

        $cicilan = Cicilan::findOrFail($request->idmaster);
        $cicilan->cicilan_nominal = $nominal;
        $cicilan->cicilan_remarks = $request->remark;
        $cicilan->cicilan_eff_date = $request->effdate;
        $cicilan->save();
        alert()->success('Success', 'Data Updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi\Cicilan  $cicilan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cicilan = Cicilan::findOrFail($request->temp_id);
        $cicilan->cicilan_is_active = 0;
        $cicilan->save();
        
        alert()->success('Success', 'Data Cancelled')->persistent('Dismiss');
        return back();
    }
}
