<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Transaksi\ReportBiaya;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportBiayaController extends Controller
{
    
    public function index(Request $request)
    {
        $truck = Truck::get();

        $data = ReportBiaya::query()->with('getTruck');

        if($request->truck){
            $data->where('rb_truck_id',$request->truck);
        }

        if($request->datefrom){
            $data->where('rb_eff_date','>=',$request->datefrom);
        }

        if($request->dateto){
            $data->where('rb_eff_date','<=',$request->dateto);
        }

        $data = $data->paginate(10);

        return view('transaksi.rbhist.index',compact('data','truck'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $truck = Truck::get();

        return view('transaksi.rbhist.create',compact('truck'));
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
            $rbhist = new ReportBiaya();
            $rbhist->rb_truck_id = $request->truck;
            $rbhist->rb_eff_date = $request->effdate;
            $rbhist->rb_nominal = $request->nominal;
            $rbhist->rb_remark = $request->remark;
            $rbhist->save();

            DB::commit();
            alert()->success('Success', 'Save Berhasil')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Save Gagal')->persistent('Dismiss');
            return back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi\ReportBiaya  $reportBiaya
     * @return \Illuminate\Http\Response
     */
    public function show(ReportBiaya $reportBiaya)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi\ReportBiaya  $reportBiaya
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportBiaya $reportBiaya)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi\ReportBiaya  $reportBiaya
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportBiaya $reportBiaya)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi\ReportBiaya  $reportBiaya
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $data = ReportBiaya::findOrFail($id);
        $data->rb_is_active = 0;
        $data->save();

        alert()->success('Success', 'Delete Berhasil')->persistent('Dismiss');
        return back();

    }
}
