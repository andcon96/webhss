<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use Illuminate\Http\Request;

class BarangMTController extends Controller
{
    public function index(Request $request)
    {
        $listbarang = Barang::get();
        $barang = Barang::query()->with('hasBonus');

        if($request->s_itemcode){
            $barang->where('id',$request->s_itemcode);
        }

        $barang = $barang->paginate(10);

        return view('setting.barang.index',compact('barang','listbarang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setting.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $barang = New Barang();
        $barang->barang_deskripsi = $request->barang;
        $barang->barang_has_bonus = $request->hasbonus;
        $barang->save();

        
        alert()->success('Success', 'Data Saved');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang, $id)
    {
        $barang = Barang::findOrFail($id);
        
        return view('setting.barang.edit',compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $barang = Barang::findOrFail($request->idmaster);
        $barang->barang_has_bonus = $request->hasbonus;
        $barang->barang_is_active = $request->active;
        $barang->save();

        alert()->success('Success', 'Data Updated');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        //
    }
}
