<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Transaksi\SJHistTrip;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratJalanLaporMTController extends Controller
{
    public function index(Request $request)
    {
        $data = SuratJalan::query()
                        ->with('getTruck.getUserDriver',
                               'getSOMaster.getCOMaster.getCustomer');

        $truck = Truck::get();

        if ($request->truck) {
            $data->where('sj_truck_id', $request->truck);
            $data = $data->orderBy('created_at', 'DESC')->paginate(10);
        } else {
            $data = [];
        }

        return view('transaksi.sjcust.index', compact('data', 'truck'));
    }

    public function laporsj($sj, $truck)
    {
        $data = SuratJalan::query()
                        ->with('getTruck.getUserDriver',
                               'getDetail.getItem',
                               'getSOMaster.getCOMaster.getCustomer')
                        ->where('id',$sj)
                        ->where('sj_truck_id',$truck)
                        ->firstOrFail();
                        
        return view('transaksi.sjcust.laporsj', compact('data','truck'));
    }

    public function updatesj(Request $request){
        DB::beginTransaction();
        try{
            // Update Master
            $sjmstr = SuratJalan::findOrFail($request->idsjmstr);
            $sjmstr->sj_conf_remark = $request->remark;
            $sjmstr->sj_conf_date = $request->effdate;
            $sjmstr->sj_status = 'Closed';
            $sjmstr->save();

            // Update Detail
            foreach($request->iddetail as $keys => $iddetail){
                $sjddet = SuratJalanDetail::findOrFail($iddetail);
                $sjddet->sjd_qty_conf = $sjddet->sjd_qty_conf + $request->qtyakui[$keys];
                $sjddet->save();
            }

            // Kirim Qxtend
            $soship = (new QxtendServices())->qxSOShip($request->all());
            if($soship === false || $soship[0] == 'error'){
                alert()->error('Error', 'Save Gagal, Error Qxtend')->persistent('Dismiss');
                DB::rollback();
                return back();
            }
            if($soship == 'nourl'){
                alert()->error('Error', 'Url Qxtend Belum disetup')->persistent('Dismiss');
                DB::rollback();
                return back();
            }
            
            DB::commit();
            alert()->success('Success', 'Surat Jalan Berhasil Disimpan')->persistent('Dismiss');
            return redirect()->route('laporSJ');
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Save Gagal silahkan dicoba berberapa saat lagi')->persistent('Dismiss');
            return back();
        }

    }

    public function catatsj($sj, $truck)
    {
        $data = SuratJalan::query()
                        ->with('getTruck.getUserDriver',
                               'getHistTrip',
                               'getSOMaster.getCOMaster.getCustomer')
                        ->where('id',$sj)
                        ->where('sj_truck_id',$truck)
                        ->firstOrFail();
                        
        return view('transaksi.sjcust.catatsj.index', compact('data'));
    }

    public function updatecatatsj(Request $request)
    {
        DB::beginTransaction();
        try{

            foreach($request->idhist as $key => $idhist){
                $sohist = SJHistTrip::findOrFail($idhist);
                $sohist->sjh_remark = $request->sj[$key];
                $sohist->save();
            }

            DB::commit();
            alert()->success('Success', 'Surat Jalan Berhasil Disimpan')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollback();
            alert()->error('Error', 'Save Gagal silahkan dicoba berberapa saat lagi')->persistent('Dismiss');
            return back();
        }
    }
}
