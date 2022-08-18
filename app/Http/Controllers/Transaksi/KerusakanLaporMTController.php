<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Jobs\EmailApprovalKerusakan;
use App\Mail\ApprovalMail;
use App\Models\Master\approval;
use App\Models\Master\Domain;
use App\Models\Master\Kerusakan;
use App\Models\Master\KerusakanStruktur;
use App\Models\Master\KerusakanStrukturDetail;
use App\Models\Master\Prefix;
use App\Models\Master\StrukturKerusakan;
use App\Models\Master\Truck;
use App\Models\Transaksi\KerusakanDetail;
use App\Models\Transaksi\KerusakanMstr;
use App\Models\Transaksi\KerusakanStukturTransaksi;
use App\Models\Transaksi\KerusakanTindakan;
use App\Services\CreateTempTable;
use App\Services\QxtendServices;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;

class KerusakanLaporMTController extends Controller
{
    public function index(Request $request)
    {
        // $this->authorize('view',[KerusakanMstr::class]);
        
        $data = KerusakanMstr::query()
            ->with(['getDetail', 'getTruck','getTruck.getUserDriver'])
         ;   
            
        
        if ($request->s_krnbr) {
            $data->where('kr_nbr', $request->s_krnbr);
        }
        if ($request->s_status) {
            $data->where('kr_status', $request->s_status);
        }
        if ($request->s_km) {
            $data->where('kr_km', $request->s_km);
        }
        if($request->s_datefrom){
            $data->where('kr_date','>=',$request->s_datefrom);
        }

        if($request->s_dateto){
            $data->where('kr_date','<=',$request->s_dateto);
        }
        
        if ($request->s_driver) {
            $data->whereRelation('getTruck', 'id', '=', $request->s_driver);
        }
        
        

        $data = $data->orderBy('created_at', 'DESC')->paginate(10);
        
        $truck = Truck::withoutGlobalScopes()->get();
        
        
        return view('transaksi.kerusakan.index', compact('data', 'truck'));
    }

    public function show($id)
    {

        $data = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver','getDetail.getStrukturTrans','getDetail.getKerusakan','getDetail.getStrukturTrans.getStrukturMaster'])->findOrFail($id);
        $jeniskerusakan = Kerusakan::get();
        
        $struktur = KerusakanStruktur::with(['getStrukturTrans' => function($q) use ($id){
            $q->where('krs_krd_det_id',$id);
        }])->get();
        $struktur2 = KerusakanStukturTransaksi::with(["getStrukturMaster"])->where('krs_krd_det_id',$id)->get();
        
             
        // 
        return view('transaksi.kerusakan.show', compact('data', 'jeniskerusakan', 'struktur'));
    }

    public function create()
    {
        // $this->authorize('create',[KerusakanMstr::class]);
        
        $jeniskerusakan = Kerusakan::get();
        $truck = Truck::withoutGlobalScopes()->get();
        return view('transaksi.kerusakan.create', compact( 'jeniskerusakan','truck'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create',[KerusakanMstr::class]);
        // if(Session::get('domain') != 'HSS'){
        //     alert()->error('Error', 'Not Allowed')->persistent('Dismiss');
        //     return back();
        // }
        // else{

            $checktruck = Truck::withoutglobalscopes()->where('id',$request->truck)->first();
            $domainnow = $checktruck->truck_domain;
            $checkkr = KerusakanMstr::where("kr_truck",$request->truck)->where(function($e){
                $e->where('kr_status','New');
                $e->orwhere('kr_status','Need Approval');
            })->first();
            
            if($checkkr){
                alert()->error('Error', 'Report already exist for : '.$checktruck->truck_no_polis);
                return back();
            }
            $checkwo = (new WSAServices())->wsawocheckloc($checktruck->truck_no_polis);
            if($checkwo === false){
                alert()->error('Error', 'No Data from QAD');
                return back();
            }
            else if($checkwo === 'nodata'){
                
                alert()->error('Error', 'Truck '.$checktruck->truck_no_polis.' already being repaired in QAD');
                return back();
            }
            
            DB::beginTransaction();
            try {
                $getrn = (new CreateTempTable())->getrnkerusakan();
                
                if ($getrn === false) {
                    alert()->error('Error', 'Report failed');
                    DB::rollBack();
                    return back();
                }
                
                $kerusakan_mstr = new KerusakanMstr();
                $kerusakan_mstr->kr_nbr = $getrn;
                $kerusakan_mstr->kr_truck = $request->truck;
                $kerusakan_mstr->kr_date = $request->tgllapor;
                $kerusakan_mstr->kr_status = 'New';
                $kerusakan_mstr->kr_domain = $domainnow;
                $kerusakan_mstr->kr_km = $request->km;
                $kerusakan_mstr->save();

                $id = $kerusakan_mstr->id;
                foreach ($request->jeniskerusakan as $key => $datas) {
                    $kerusakan_detail = new KerusakanDetail();
                    $kerusakan_detail->krd_kr_mstr_id = $id;
                    $kerusakan_detail->krd_kerusakan_id = $datas;
                    $kerusakan_detail->krd_note = $request->remarkslain[$key];
                    $kerusakan_detail->save();
                }

                
                $prefix = Prefix::firstOrFail();
                
                $prefix->prefix_kr_rn = substr($getrn, 2, 6);
                $prefix->save();

                DB::commit();
                alert()->success('Success', 'Report created')->persistent('Dismiss');
                return back();
            } catch (Exception $e) {

                alert()->error('Error', 'Failed to create data')->persistent('Dismiss');
                return back();
            }
        // }
    }

    public function edit($id)
    {
        $data = KerusakanMstr::with(["getDetail.getStrukturTrans",'getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver'])->findOrFail($id);
        $jeniskerusakan = Kerusakan::get();
        
        // $this->authorize('update',[KerusakanMstr::class,$data]);

        return view('transaksi.kerusakan.edit', compact('data', 'jeniskerusakan'));
    }

    public function update(Request $request)
    {
        
        $data = KerusakanMstr::findOrfail($request->idmaster);
        if($data->kr_status == "Close"){
            alert()->error('Error', 'Report is closed')->persistent('Dismiss');
            return back();
        }
        // $this->authorize('update',[KerusakanMstr::class,$data]);
        DB::beginTransaction();
        try {
            $mstr = KerusakanMstr::where('id',$request->idmaster)->first();
            $mstr->kr_km = $request->km;
            $mstr->save();
            foreach ($request->iddetail as $key => $datas) {
                $detail = KerusakanDetail::firstOrNew(['id' => $datas]);
                
                if ($request->operation[$key] == 'R') {
                    $detail->delete();
                } else {
                    
                    $detail->krd_kr_mstr_id = $request->idmaster;
                    $detail->krd_kerusakan_id = $request->jeniskerusakan[$key];
                    $detail->krd_note = $request->remarkslain[$key];

                    $detail->save();
                }
                
            }

            DB::commit();
            alert()->success('Success', 'Report updated')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            
            alert()->error('Error', 'Report failed to update')->persistent('Dismiss');
            return back();
        }
        
    }

    public function destroy(Request $request)
    {
        
        // $this->authorize('delete',[KerusakanMstr::class]);

        DB::beginTransaction();
        try {
            $data = KerusakanMstr::findOrFail($request->temp_id);
            $data->kr_status = 'Cancelled';
            $data->save();

            DB::commit();
            alert()->success('Success', 'Report cancelled')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            
            alert()->error('Error', 'Failed to cancel')->persistent('Dismiss');
            return back();
        }
    
    }

    public function assignkr($id)
    {
        $data = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver','getDetail.getStrukturTrans'])->findOrFail($id);
        
        $jeniskerusakan = Kerusakan::get();
        $struktur = KerusakanStruktur::where('ks_isactive',1)->get();

        return view('transaksi.kerusakan.assignkr', compact('data', 'jeniskerusakan', 'struktur'));
    }

    public function upassignkr($id, Request $request)
    {
        // $this->authorize('custompolicy',[KerusakanMstr::class]);
        
        $nopol = $request->truck;
        $wonbr = $request->sonbr;
        
        $kerusakanlist = [];
        $rusaknbr = $request->sonbr;
        $nopolnbr = $request->truck;
        
        // validasi approval ada atau tidak
        $emailto = approval::get();
        if(is_null($emailto)){
            alert()->error('Error','Harap setting terlebih dahulu email untuk Approver di Approval Maintenance');
            return redirect()->route('laporkerusakan.index');
        }

        //kirim email dan update status
        $kerusakan = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver','getDetail.getStrukturTrans'])->where('kr_status', 'New')->findOrFail($request->idmaster);
        
        $needappr = 0;
        
        foreach($kerusakan->getDetail as $key => $data){
            $needappr = $data->getKerusakan->kerusakan_need_approval == 1 ? 1 : $needappr;
            // array_push($needappr,$data->getKerusakan->kerusakan_need_approval);
            array_push($kerusakanlist,$data->getKerusakan->kerusakan_desc);
        }
        
        switch($needappr){
            case 1:
                DB::beginTransaction();
                try{
                    $kerusakan->kr_status = 'Need Approval';
                    $kerusakan->save();
                    foreach($request->iddetail as $key => $data){
                        if(!empty($request->remarks[$key])){
                            KerusakanDetail::where('id',$data)->update(['krd_remarks' => $request->remarks[$key]]);
                        }
                    }

                    foreach($request->struk_desc as $key=>$data){
                        $kerusakandtl = new KerusakanStukturTransaksi();
                        $kerusakandtl->krs_krd_det_id = $request->struk_detail_id[$key];
                        $kerusakandtl->krs_kerusakan_struktur_id = $request->struk_mekanik_id[$key];
                        $kerusakandtl->krs_desc = $request->struk_desc[$key];
                        $kerusakandtl->save();    
                    }
                    $pesan = 'New Truck Breakdown Approval';
                    

                    EmailApprovalKerusakan::dispatch(
                        $pesan,
                        $wonbr,
                        $nopol,
                        $kerusakanlist,
                        $emailto,
                        
                    );

                    DB::commit();
                    alert()->success('Success','Kerusakan berhasil di assign');
                    return redirect()->route('laporkerusakan.index');
                }catch(Exception $err){
                    DB::rollback();
                    
                    alert()->error('Error','Kerusakan gagal di assign');
                    return redirect()->route('laporkerusakan.index');
                }
                break;
            
            case 0 :
                DB::beginTransaction();
                try{
                    $kerusakan->kr_status = 'Done';
                    $kerusakan->save();
                    foreach($request->iddetail as $key => $data){
                        if(!empty($request->remarks[$key])){
                            KerusakanDetail::where('id',$data)->update(['krd_remarks' => $request->remarks[$key]]);
                        }
                    }
                    foreach($request->struk_desc as $key=>$data){
                        $kerusakandtl = new KerusakanStukturTransaksi();
                        $kerusakandtl->krs_krd_det_id = $request->struk_detail_id[$key];
                        $kerusakandtl->krs_kerusakan_struktur_id = $request->struk_mekanik_id[$key];
                        $kerusakandtl->krs_desc = $request->struk_desc[$key];
                        $kerusakandtl->save();    
                    }
                    $qxkerusakan = (new QxtendServices())->qxWOkerusakan($rusaknbr,$nopolnbr);
                    if($qxkerusakan[0] == false){
                        DB::rollback();
                        
                        alert()->error('Error','Qxtend gagal, '.$qxkerusakan[1]);
                        return back();
                    }else if($qxkerusakan[0] == true){
                        DB::commit();
                        alert()->success('Success','Assign report success');
                        return redirect()->route('laporkerusakan.index');
                    }

                    
                }catch(Exception $err){
                    DB::rollback();
                    
                    alert()->error('Error','Assign report failed');
                    return redirect()->route('laporkerusakan.index');
                }
                break;
        }
        
    }

    public function assingremarkskr($id)
    {
        $data = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver','getDetail.getStrukturTrans','getDetail.getTindakan' => function($q) use ($id){
            $q->orderBy('id','desc');
        }])->findOrFail($id);
        
        
        $jeniskerusakan = Kerusakan::get();
        $struktur = KerusakanStruktur::where('ks_isactive',1)->get();

        return view('transaksi.kerusakan.assignremarkskr', compact('data', 'jeniskerusakan', 'struktur'));
    }

    public function upassignremarkskr($id, Request $request)
    {
        // dd($request->all());
        // $this->authorize('custompolicy',[KerusakanMstr::class]);
        DB::beginTransaction();
        try{
            foreach($request->iddetail as $key => $data){
                if(!empty($request->remarks[$key])){
                    $kt = new KerusakanTindakan();
                    $kt->krt_krd_id = $data;
                    $kt->krt_remarks = $request->remarks[$key];
                    $kt->krt_date = $request->dateinput[$key];
                    $kt->save();
                }
            }
            
            
            DB::commit();
            alert()->success('Success','Tindakan berhasil di assign');
            return redirect()->route('laporkerusakan.index');
        }catch(Exception $err){
            DB::rollback();
            
            alert()->error('Error','Tindakan gagal di assign');
            return redirect()->route('laporkerusakan.index');
        }
                
        
        
    } 

    public function krhistoryview($id,Request $request){
        
        $data = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver','getDetail.getStrukturTrans','getDetail.getTindakan' => function($q) use ($id){
            $q->orderBy('id','desc');
        }])->findOrFail($id);
        $tindakanlist = [];
        if($request->s_jeniskerusakan){
            
            $tindakanlist= KerusakanTindakan::with('getDetail','getDetail.getKerusakan')->where('krt_krd_id',$request->s_jeniskerusakan)->orderBy('krt_date','desc')->paginate(10);    
        }
        // dd($tindakanlist);
        $id = $id;
        $jeniskerusakan = Kerusakan::get();
        $struktur = KerusakanStruktur::where('ks_isactive',1)->get();
        return view('transaksi.kerusakan.assignkrremakrshistory', compact('id','data', 'jeniskerusakan', 'struktur','tindakanlist'));
        
    }
}