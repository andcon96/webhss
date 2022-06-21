<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Rute;
use App\Models\Master\RuteHistory;
use App\Models\Master\TipeTruck;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $truck_type = TipeTruck::paginate(10);
        
        return view('setting.rute.index', compact('truck_type'));
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function show(Rute $rute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function edit(Rute $rute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rute $rute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rute $rute)
    {
        //
    }

    public function viewDetail($id)
    {
        $rute_data = Rute::with(['getShipFrom','getShipTo','getTipe'])->where('rute_tipe_id',$id)->paginate(10);
        return view('setting.rute.indexdetail', compact('rute_data'));
        //
    }

    public function viewHistory($id)
    {
        
        $history_data = RuteHistory::with(['getRute.getShipTo','getRute.getShipFrom','getRute.getTipe'])->where('rute_tipe_id',$id)->paginate(10);
        return view('setting.rute.indexhistory', compact('history_data'));
        //
    }
}
