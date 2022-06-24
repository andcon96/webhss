<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\TipeTruck;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TruckTipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TipeTruck::query();
        

        if($request->s_tipetruck){
            $data->where('id',$request->s_tipetruck);
        }
        $data = $data->paginate(10);
        // dd($data);
        return view('setting.tipetruck.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $tipetruck = new TipeTruck();
        DB::beginTransaction();
        try{
            $tipetruck->tt_code = $request->code;
            $tipetruck->tt_desc = $request->desc;
            $tipetruck->save();
            DB::commit();
            alert('Success','Create Tipe Truck Success');
            return redirect()->route('tipetruck.index');
        }
        catch(Exception $err){
            DB::rollBack();
            alert()->error('Error','Create Tipe Truck Failed');
            return redirect()->route('tipetruck.index');
        }
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\TipeTruck  $tipeTruck
     * @return \Illuminate\Http\Response
     */
    public function show(TipeTruck $tipeTruck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\TipeTruck  $tipeTruck
     * @return \Illuminate\Http\Response
     */
    public function edit(TipeTruck $tipeTruck)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\TipeTruck  $tipeTruck
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipeTruck $tipeTruck)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\TipeTruck  $tipeTruck
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipeTruck $tipeTruck)
    {
        //
    }
}
