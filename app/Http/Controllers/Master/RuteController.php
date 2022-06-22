<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\RuteImport;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Rute;
use App\Models\Master\RuteHistory;
use App\Models\Master\ShipFrom;
use App\Models\Master\TipeTruck;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $truck_type = TipeTruck::paginate(10);
        
        return view('setting.rute.index', compact('truck_type'));
        //
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
        $ruteid = $request->idrute;
        $harga = $request->harga;

        $lasthistory = RuteHistory::where('history_rute_id',$ruteid)->where('history_is_active',1)->first();
        DB::beginTransaction();
        
            try{
                if($lasthistory){
                    RuteHistory::where('history_rute_id',$ruteid)->where('history_is_active',1)
                    ->update([
                        'history_is_active' => 0,
                        'history_last_active' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                    ]);
                }
                $rutehist = new RuteHistory();
                $rutehist->history_rute_id = $ruteid;
                $rutehist->history_harga = $harga;
                $rutehist->history_is_active = 1;
                $rutehist->history_user = Auth::user()->username;
                $rutehist->save();
                DB::commit();
                alert()->success('Success', 'History berhasil diperbarui');
                return back();
            }
            catch(Exception $err){
                DB::rollback();
                alert()->error('Error', 'Terjadi kesalahan');
                return back();
            }
        }
        

        //
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function show(Rute $rute)
    {
        
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function edit(Rute $rute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rute $rute)
    {
        
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rute $rute)
    {
        //
    }

    public function viewDetail($id)
    {
        
        $rute_data = Rute::with(['getShipFrom','getShipTo','getTipe'])->where('rute_tipe_id',$id)->whereRelation('getShipTo','cs_domain',Session::get('domain'))->paginate(10);
        
        return view('setting.rute.indexdetail', compact('rute_data'));
        //
    }

    public function viewHistory($id)
    {
        
        $history_data = RuteHistory::with(['getRute.getShipTo','getRute.getShipFrom','getRute.getTipe'])
        ->where('history_rute_id',$id)->whereRelation('getRute.getShipTo','cs_domain',Session::get('domain'))->orderBy('history_is_active','desc')->orderBy('history_last_active','desc')->get();
        $rute = Rute::with('getShipFrom','getTipe','getShipTo')->where('id',$id)->whereRelation('getShipTo','cs_domain',Session::get('domain'))->first();
        
        return view('setting.rute.indexhistory', compact('history_data','rute'));
        //
    }

    public function downloadtemplate(){
        $file = public_path()."/downloads/template_hss.xlsx";
        $headers = array('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        return response()->download($file, 'template_hss.xlsx', $headers); 
    }
    public function importexcel(Request $request){
        // dd($request->all());
        $this->validate($request, [
            'btnexcel' => 'required|file|mimes:xlsx'
        ]);

        try {
            $file = $request->file('btnexcel');
            
            $namafile = rand() . $file->getClientOriginalName();
            // dd($namafile);
            $file->move('excel', $namafile);
            $ruteimport = new RuteImport();            
            Excel::import($ruteimport, public_path('/excel/' . $namafile));
            File::delete(public_path('/excel/' . $namafile));
            
            unset($ruteimport->collection[0]);
            $rute_collect = $ruteimport->collection;
            $arrayrute = [];
            $arrayhistory = [];
            $i = 0;
            $tipetruk_or = TipeTruck::get();
            $shipfrom_or = ShipFrom::get();
            $shipto_or = CustomerShipTo::get();
            $stringtipe = '';
            $stringshipfrom = '';
            $stringshipto = '';
            $stringharga = '';
            foreach($rute_collect as $ri){

                $tipetruk = $tipetruk_or->where('tt_code',$ri['tipe'])->first();
                $shipfrom = $shipfrom_or->where('sf_code',$ri['shipfrom'])->first();
                $shipto = $shipto_or->where('cs_shipto',$ri['shipto'])->first();
                $harga = $ri['harga'];
                $valtipetruk = $tipetruk->id;
                $valshipfrom = $shipfrom->id;
                $valshipto = $shipto->id;
            
                $stringtipe.=$valtipetruk.';';
                $stringshipfrom.=$valshipfrom.';';
                $stringshipto.=$valshipto.';';
                $stringharga.=$harga.';';

                $i++;
            
            }
            
            $stringtipe = rtrim($stringtipe,';');
            $stringshipfrom = rtrim($stringshipfrom,';');
            $stringshipto = rtrim($stringshipto,';');
            $stringharga = rtrim($stringharga,';');
            return view('setting.rute.viewexcel', compact('rute_collect','stringtipe','stringshipfrom','stringshipto','stringharga'));
            
        } catch (Exception $e) {
            
            alert()->error('Error', 'Upload excel failed, Please recheck data');
            return redirect()->route('rute.index');
        }
    }
    public function importrute(Request $request){
        

        $tipeidstring = $request->tipe;
        $shipfromstring = $request->shipfrom;
        $shiptostring = $request->shipto;
        $hargastring = $request->harga;

        $tipeid = explode(';',$tipeidstring);
        $shipfrom = explode(';',$shipfromstring);
        $shipto = explode(';',$shiptostring);
        $harga = explode(';',$hargastring);
        $rutearray = [];
        $arrayinsert = [];
        
        DB::beginTransaction();
        try{
            foreach($tipeid as $index => $data){
                
                $rute = Rute::firstOrCreate([
                            'rute_tipe_id'          =>  $data,
                            'rute_shipfrom_id'      =>  $shipfrom[$index],
                            'rute_customership_id'  =>  $shipto[$index]
    
                ]);
                
                $ruteid = $rute->id;
                if(!in_array($ruteid,$rutearray)){
                    
                    array_push($rutearray,$ruteid);
                    
                }
            
                $arrayinsert[$index] = [
                    'history_rute_id' => $ruteid,
                    'history_harga' => $harga[$index],
                    'history_is_active' => 1,
                    'history_user' => Auth::user()->username
                ];
            }
            
            RuteHistory::whereIn('history_rute_id',$rutearray)->where('history_is_active',1)->update([
                'history_is_active' => 0,
                'history_last_active' => Carbon::now()->toDateTimeString()
            ]);
            RuteHistory::insert($arrayinsert);

            
            DB::commit();
            alert()->success('Success', 'History berhasil di import');
            return redirect()->route('rute.index');
        }
        catch(Exception $err){
            DB::rollBack();
            
            alert()->error('Error', 'Import Gagal');
            return back();
            
        }
        
    }
}
