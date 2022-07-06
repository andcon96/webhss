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
        $data = KerusakanStruktur::where('ks_isactive',1)->get();

        return view('setting.struktur.index',compact('data'));
    }

    public function store(Request $request){
        dd($request->all());
        DB::beginTransaction();

        try{
            
            foreach($request->order as $key => $datas){
                $newdata = new KerusakanStruktur;
                
                $newdata->ks_desc = $request->desc[$key];
                $newdata->ks_order = $request->order[$key];
                $newdata->save();
            }

            DB::commit();
            alert()->success('Success', 'Struktur Updated');
        }catch(Exception $e){

            DB::rollBack();
            dd($e);
            alert()->error('Error', 'Failed to Update');
        }

        return back();
    }

    public function activestruc($id){
        $ks = KerusakanStruktur::where('id',$id)->first();
        DB::beginTransaction();
        try{
            if($ks->ks_isactive == 1){
               $ks->ks_isactive = 0; 
            }
            else{
                $ks->ks_isactive = 1;
            }
            $ks->save();
            DB::commit();
            alert()->success('Success', 'Struktur Deactivated');
            return back();
        }catch(Exception $err){
            DB::rollback();
            alert()->error('Error', 'Failed to Deactivated');
            return back();
        }

    }
}
