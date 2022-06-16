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
        $prefix->prefix_so = $request->prefixso;
        $prefix->rn_so = $request->rnso;
        $prefix->prefix_kerusakan = $request->prefixkerusakan;
        $prefix->rn_kerusakan = $request->rnkerusakan;
        $prefix->save();

        alert()->success('Success', 'Prefix Updated');
        return back();
    }

}
