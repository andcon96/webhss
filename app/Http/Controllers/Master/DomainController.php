<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function index()
    {
        $domain = Domain::get();

        return view('setting.domain.index',compact('domain'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            foreach($request->iddomain as $key => $data){
                $domain = Domain::firstOrNew(['id' => $data]);
                if($request->op[$key] == 'R'){
                    $domain->delete();
                }else{
                    $domain->domain_code = $request->code[$key];
                    $domain->domain_desc = $request->desc[$key];
                    $domain->domain_so_prefix = $request->soprefix[$key];
                    $domain->domain_so_rn = $request->sorn[$key];
                    $domain->domain_kr_prefix = $request->krprefix[$key];
                    $domain->domain_kr_rn = $request->krrn[$key];
                    $domain->save();
                }
            }

            DB::commit();
            alert()->success('Success','Domain Berhasil Diupdate');
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            alert()->error('Error','Terjadi kesalahan, Silahkan dicoba lagi');
        }

        return back();
    }
}
