<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\KerusakanStruktur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrukturKerusakanController extends Controller
{
    public function index()
    {
        // dd('1');
        $data = KerusakanStruktur::get();

        return view('setting.struktur.index',compact('data'));
    }

    public function store(Request $request){
        // dd($request->all());
        DB::beginTransaction();

        try{
            KerusakanStruktur::whereNotNull('id')->delete();
            foreach($request->order as $key => $datas){
                $newdata = KerusakanStruktur::firstOrNew(['ks_order' => $datas]);
                $newdata->ks_desc = $request->desc[$key];
                $newdata->save();
            }

            DB::commit();
            alert()->success('Success', 'Struktur Updated');
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Failed to Updated');
        }

        return back();
    }
}
