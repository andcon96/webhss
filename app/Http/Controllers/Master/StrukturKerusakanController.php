<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\StrukturKerusakan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrukturKerusakanController extends Controller
{
    public function index()
    {
        // dd('1');
        $data = StrukturKerusakan::get();

        return view('setting.struktur.index',compact('data'));
    }

    public function store(Request $request){
        // dd($request->all());
        DB::beginTransaction();

        try{
            StrukturKerusakan::whereNotNull('id')->delete();
            foreach($request->order as $key => $datas){
                $newdata = StrukturKerusakan::firstOrNew(['slk_order' => $datas]);
                $newdata->slk_desc = $request->desc[$key];
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
