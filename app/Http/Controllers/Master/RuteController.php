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
    public function index(Request $request)
    {
        $truck_type = TipeTruck::query();
            
        
        if ($request->s_krnbr) {
            $truck_type->where('tt_code', $request->s_ttcode);
        }

        $truck_type = $truck_type->orderBy('created_at', 'DESC')->paginate(10);
                
        return view('setting.rute.index', compact('truck_type'));
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
        $sangu = str_replace(',','',$request->sangu);
        $ongkos = str_replace(',','',$request->ongkos);
        // dd($sangu,$ongkos);
        $lasthistory = RuteHistory::where('history_rute_id',$ruteid)->where('history_is_active',1)->first();
        DB::beginTransaction();
    
        try{

            $rutehist = new RuteHistory();
            $rutehist->history_rute_id = $ruteid;
            // $rutehist->history_harga = $harga;
            $rutehist->history_sangu = $sangu;
            $rutehist->history_ongkos = $ongkos;
            $rutehist->history_is_active = 1;
            $rutehist->save();
            DB::commit();
            alert()->success('Success', 'History berhasil diperbarui');
            return back();
        }
        catch(Exception $err){
            DB::rollback();
            // dd($err);
            alert()->error('Error', 'Terjadi kesalahan');
            return back();
        }
    }

    public function viewDetail(Request $request,$id)
    {
        
        $rute_data = Rute::with(['getTipe'])->Join('shipfrom','rute_shipfrom_id','shipfrom.id')
        ->Join('customership','rute_customership_id','customership.id')
        ->selectRaw('rute.*,customership.*,shipfrom.*,rute.id')
        ->where('rute_tipe_id',$id);
        
        if($request->s_shipfrom){
            $rute_data->where('rute_shipfrom_id', $request->s_shipfrom);
            
        }
        if($request->s_shipto){
            $rute_data->where('rute_customership_id', $request->s_shipto);
        }
        

        $rute_data = $rute_data->OrderBy('sf_code','asc')->orderBy('cs_shipto','asc')->paginate(10);
        
        $shipfrom = ShipFrom::get();
        $shipto = CustomerShipTo::get();
        
        
        $id = $id;
        return view('setting.rute.indexdetail', compact('rute_data','shipfrom','shipto','id'));
        //
    }

    public function viewHistory($oldid,$id)
    {
        
        $history_data = RuteHistory::with(['getRute.getShipTo','getRute.getShipFrom','getRute.getTipe'])
        ->where('history_rute_id',$id)->orderBy('history_is_active','desc')->orderBy('history_last_active','desc')->get();
        $rute = Rute::with('getShipFrom','getTipe','getShipTo')->where('id',$id)->first();

        $id = $oldid;
        
        return view('setting.rute.indexhistory', compact('history_data','rute','id'));
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
            $stringongkos = '';
            $stringsangu = '';
            foreach($rute_collect as $ri){

                $tipetruk = $tipetruk_or->where('tt_code',$ri['tipe'])->first();
                $shipfrom = $shipfrom_or->where('sf_code',$ri['shipfrom'])->first();
                $shipto = $shipto_or->where('cs_shipto',$ri['shipto'])->first();
                $harga = $ri['harga'];
                $ongkos = $ri['ongkos'];
                $sangu = $ri['sangu'];
                $valtipetruk = $tipetruk->id;
                $valshipfrom = $shipfrom->id;
                $valshipto = $shipto->id;
            
                $stringtipe.=$valtipetruk.';';
                $stringshipfrom.=$valshipfrom.';';
                $stringshipto.=$valshipto.';';
                $stringharga.=$harga.';';
                $stringongkos.=$ongkos.';';
                $stringsangu.=$sangu.';';

                $i++;
            
            }
            
            $stringtipe = rtrim($stringtipe,';');
            $stringshipfrom = rtrim($stringshipfrom,';');
            $stringshipto = rtrim($stringshipto,';');
            $stringharga = rtrim($stringharga,';');
            $stringongkos = rtrim($stringongkos,';');
            $stringsangu = rtrim($stringsangu,';');
            return view('setting.rute.viewexcel', compact('rute_collect','stringtipe','stringshipfrom','stringshipto','stringharga','stringongkos','stringsangu',));
            
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
        $ongkosstring = $request->ongkos;
        $sangustring = $request->sangu;

        $tipeid = explode(';',$tipeidstring);
        $shipfrom = explode(';',$shipfromstring);
        $shipto = explode(';',$shiptostring);
        $harga = explode(';',$hargastring);
        $ongkos = explode(';',$ongkosstring);
        $sangu = explode(';',$sangustring);
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
                    'history_ongkos' => $ongkos[$index],
                    'history_sangu' => $sangu[$index],
                    'history_is_active' => 1,

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

    public function newrute(Request $request){
        $cekrute = Rute::where('rute_tipe_id',$request->tipecode)->where('rute_shipfrom_id',$request->shipfrom)->where('rute_customership_id',$request->shipto)->first();
        if($cekrute){
            alert()->error('Error', 'Data Exist');
            return back();
        }

        DB::beginTransaction();
        try{
            $rute = new Rute();
            $rute->rute_tipe_id = $request->tipecode;
            $rute->rute_shipfrom_id = $request->shipfrom;
            $rute->rute_customership_id = $request->shipto;
            $rute->save();
            DB::commit();
            alert()->success('Success', 'Rute berhasil di tambah');
            return back();
        }
        catch(Exception $err){
            DB::rollback();
            alert()->error('Error', 'Rute gagal di tambah');
            return back();
        }
    }

    public function loadrutefirst(Request $request)
    {
        $tipetruck = TipeTruck::get();
        $shipfrom = ShipFrom::get();
        $shipto = CustomerShipTo::get();
        $data = [];

        foreach($tipetruck as $key => $tipetrucks){
            foreach($shipfrom as $shipfroms){
                foreach($shipto as $shiptos){
                    $data[] = [
                        'rute_tipe_id' => $tipetrucks->id,
                        'rute_shipfrom_id' => $shipfroms->id,
                        'rute_customership_id' => $shiptos->id,
                    ];
                }
            }
            Rute::insert($data);
            $data = [];
        }

    }

    public function loadhistoryrute(Request $request)
    {
        ini_set('max_execution_time', 360);

        if (($open = fopen(public_path() . "/HSST_loosing.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $history[] = $data;
            }

            $tipetruck = '';
            DB::beginTransaction();
            try{

                foreach($history as $histories){
                    if($histories[3] != ''){
                        $kodetruck = $histories[4];
                        $kodetruck == '2EXL' ? $tipetruck = 1 :
                        ($kodetruck == '3EXL' ?  $tipetruck = 2 :
                        ($kodetruck == 'Semi' ?  $tipetruck = 3 :
                        ($kodetruck == 'LD' ?  $tipetruck = 4 :
                        ($kodetruck == '20"' ?  $tipetruck = 5 :
                        ($kodetruck == '40"' ?  $tipetruck = 6 : '')))));
        
                        $shipfrom = ShipFrom::where('sf_code',$histories[1])->first();
        
                        $shipto = CustomerShipTo::where('cs_shipto', 'LIKE' ,'%'.$histories[3])->get();
                        
                        $insertData = [];
        
                        foreach($shipto as $shiptos){
                            $rute = Rute::where('rute_tipe_id',$tipetruck)
                                        ->where('rute_shipfrom_id',$shipfrom->id)
                                        ->where('rute_customership_id',$shiptos->id)->first();
                            if($rute){
                                $insertData[] = [
                                    'history_rute_id' => $rute->id,
                                    'history_sangu' => trim(str_replace('.','',$histories[5])),
                                    'history_ongkos' => 0,
                                    'history_is_active' => 1,
                                ];
                            }
                        }
                        RuteHistory::insert($insertData);
                        $insertData = [];

                    }
                }
                DB::commit();
            }
            catch(Exception $err){
                DB::rollback();
                dd($histories,$err);
            }
            

            fclose($open);
        }
    }

    public function loadhistoryrutedetail()
    {
        if (($open = fopen(public_path() . "/HSS_Container.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $history[] = $data;
            }
            $rutearray = [];
            $tipetruck = '';
            foreach($history as $histories){
                
                if(!empty($histories[2])){
                    $tipebefore = str_replace('*','"',substr($histories[0],-3));
                    $tipeid = TipeTruck::where('tt_code',$tipebefore)->first();
                    if(isset($tipeid)){
                        $shipto = CustomerShipTo::where('cs_shipto','like','%'.$histories[2])->get();
                        if(count($shipto) > 0){
                            
                            foreach($shipto as $st){
                                $rute = Rute::where('rute_tipe_id',$tipeid->id)->where('rute_customership_id',$st->id)->get();
                                foreach ($rute as $rt){
                                    $rutearray[] = [
                                        'history_rute_id'       => $rt->id,
                                        'history_harga'         => 0,
                                        'history_sangu'         => (int)$histories[3],
                                        'history_ongkos'        => (int)$histories[4],
                                        'history_is_active'     => 1,
                                        'history_last_active'   => Carbon::now()->toDateTimeString(),
                                        'history_user'          => 1,
                                        'created_at'            => Carbon::now()->toDateTimeString(),
                                        'updated_at'            => Carbon::now()->toDateTimeString()
                                    ];
                                }
                            }
                        }
                    }
                }
                RuteHistory::insert($rutearray);
                $rutearray = [];
            }
            
            fclose($open);
            
        }
    }
    
    public function historychangestatus(Request $request){
        
        try{
            RuteHistory::where('id',$request->idhistory)->where('history_is_active',1)
                ->update([
                    'history_is_active' => 0,
                    'history_last_active' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                ]);
            DB::commit();
            alert()->success('Success', 'Rute berhasil di nonaktif');
            return back();
        }
        catch(Exception $err){
            DB::rollback();
            alert()->error('Error', 'Rute gagal di nonaktif');
            return back();
        }

    }
}
