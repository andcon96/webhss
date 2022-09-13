<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ShipFrom;
use Illuminate\Http\Request;

class ShipFromController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = ShipFrom::query();
        
        if($request->s_sfcode){
            $data->where('id',$request->s_sfcode);
        }

        $data = $data->orderBy('id','desc')->paginate(10);
        
        return view('setting.customer.shipfrom.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quernumber = ShipFrom::where('sf_code','like','SBY-%')->orderBy('sf_code','desc')->first();
        $lastnumber = '';
        
        if($quernumber){
            
            $number = $quernumber->sf_code;
            
            $strangka = substr($number,(strpos($number,'-')+1),strlen($number));
            
            $newangka = (string)((int)$strangka +1);
            $selisihangka = 3 - strlen($newangka);
            
            $lastnumber = 'SBY-'.str_pad($newangka,$selisihangka,0,STR_PAD_LEFT);
        }
        else{
            $lastnumber = 'SBY-01';
        }
        return view('setting.customer.shipfrom.create',compact('lastnumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shipfrom = new ShipFrom();
        $shipfrom->sf_code = $request->sfcode;
        $shipfrom->sf_desc = $request->sfdesc;
        $shipfrom->sf_is_active = 1;
        $shipfrom->save();

        
        alert()->success('Success', 'Ship From Successfully Created');
        return redirect()->route('shipfrom.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\ShipFrom  $shipFrom
     * @return \Illuminate\Http\Response
     */
    public function edit(ShipFrom $shipFrom,$id)
    {
        $data = ShipFrom::findOrFail($id);

        return view('setting.customer.shipfrom.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\ShipFrom  $shipFrom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShipFrom $shipFrom, $id)
    {
        $data = ShipFrom::findOrFail($id);
        $data->sf_desc = $request->sfdesc;
        $data->save();

        alert()->success('Success', 'Ship From Successfully Updated');
        return redirect()->route('shipfrom.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\ShipFrom  $shipFrom
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShipFrom $shipFrom,$id)
    {
        $data = ShipFrom::findOrFail($id);
        $data->sf_is_active == 0 ? $data->sf_is_active = 1 : $data->sf_is_active = 0;
        $data->save();
        // dd($data);
        
        alert()->success('Success', 'Ship from Status Successfully Changed');
        return redirect()->route('shipfrom.index');
    }
}
