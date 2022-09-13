<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\BonusBarang;
use App\Models\Master\TipeTruck;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusBarangController extends Controller
{
    public function index(Request $request)
    {
        $listbarang = Barang::get();
        $barang = Barang::query();

        if($request->s_itemcode)
        {
            $barang->where('id',$request->s_itemcode);
        }

        $barang = $barang->paginate(10);

        return view('setting.bonusbarang.index',compact('barang','listbarang'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            $bonus = new BonusBarang();
            $bonus->bb_barang_id = $request->barang;
            $bonus->bb_tipe_truck_id = $request->tipetruck;
            $bonus->bb_qty_start = $request->qtyawal;
            $bonus->bb_qty_end = $request->qtyakhir;
            $bonus->bb_price = $request->nominal;
            $bonus->save();
            
            alert()->success('Success', 'Data Saved');
            DB::commit();
            return back();
        }catch(Exception $e){
            alert()->error('Error', 'Gagal Save');
            DB::rollback();
            return back();
        }
    }

    public function bonusbarangdetail($id, Request $request)
    {
        $curbarang = Barang::findOrFail($id);
        $tipetruck = TipeTruck::get();
        $id = $id;
        $bonusbarang = BonusBarang::query()
                    ->with(['getBarang','getTipeTruck'])
                    ->where('bb_barang_id',$id);

        if($request->s_tipetruck){
            $bonusbarang->where('bb_tipe_truck_id',$request->s_tipetruck);
        }

        $bonusbarang = $bonusbarang->paginate(10);

        return view('setting.bonusbarang.detail',compact('bonusbarang','id','tipetruck','curbarang'));
    }

    public function updatedetail(Request $request)
    {
        DB::beginTransaction();
        try{
            $bonusbarang = BonusBarang::findOrFail($request->iddetail);
            $bonusbarang->bb_qty_start = $request->qtyawal;
            $bonusbarang->bb_qty_end = $request->qtyakhir;
            $bonusbarang->bb_price = $request->nominal;
            $bonusbarang->bb_is_active = $request->status;
            $bonusbarang->save();

            alert()->success('Success', 'Data Updated');
            DB::commit();
            return back();
        }catch(Exception $e){
            alert()->error('Error', 'Gagal Save');
            DB::rollback();
            return back();
        }
    }

}
