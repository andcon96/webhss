<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\GandenganMstr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GandenganMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $gandengmstr = GandenganMstr::query();
        if($request->domain){
            $gandengmstr->where('gandeng_domain',$request->domain);
        }
        if($request->gandengan){
            $gandengmstr->where('gandeng_domain',$request->domain);
        }
        $gandengmstr = $gandengmstr->paginate(10);
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
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function show(GandenganMstr $gandenganMstr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function edit(GandenganMstr $gandenganMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GandenganMstr $gandenganMstr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GandenganMstr  $gandenganMstr
     * @return \Illuminate\Http\Response
     */
    public function destroy(GandenganMstr $gandenganMstr)
    {
        //
    }

    public function loadgandengan()
    {
        if (($open = fopen(public_path() . "/gandengan.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $gandeng[] = $data;
            }

            $gandenganarr = [];

            foreach($gandeng as $gandengan){  
                if(!empty($gandengan[2])){
                    $gandenganarr[] = [
                        'gandeng_domain'        => (string)$gandengan[0],
                        'gandeng_code'          => (string)$gandengan[2],
                        'gandeng_is_active'     => 1,
                        'created_at'            => Carbon::now()->toDateTimeString(),
                        'updated_at'            => Carbon::now()->toDateTimeString()
                    ];
                }
            }
            GandenganMstr::insert($gandenganarr);
            $gandenganarr = [];
            fclose($open);
            
        }
    }
}
