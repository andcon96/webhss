<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Prefix;
use Illuminate\Http\Request;

class PrefixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prefix = Prefix::first();

        return view('setting.prefix.index',compact('prefix'));
    }

    public function store(Request $request)
    {
        $prefix = Prefix::firstOrNew(['id'=>'1']);
        $prefix->prefix_co = $request->prefixco;
        $prefix->prefix_co_rn = $request->rnco;
        $prefix->prefix_so = $request->prefixso;
        $prefix->prefix_so_rn = $request->rnso;
        $prefix->prefix_sj = $request->prefixspk;
        $prefix->prefix_sj_rn = $request->rnspk;
        $prefix->prefix_kr = $request->prefixkerusakan;
        $prefix->prefix_kr_rn = $request->rnkerusakan;
        $prefix->prefix_iv = $request->prefixiv;
        $prefix->prefix_iv_rn = date('Y').$request->rniv;
        $prefix->save();

        alert()->success('Success', 'Prefix Updated');
        return back();
    }

}
