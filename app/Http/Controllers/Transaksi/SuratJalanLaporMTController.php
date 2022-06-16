<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use App\Models\Transaksi\SalesOrderDetail;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SalesOrderSangu;
use App\Models\Transaksi\SOHistTrip;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratJalanLaporMTController extends Controller
{
    public function index(Request $request)
    {
        $data = SalesOrderSangu::query()
            ->with(['getTruckDriver.getTruck', 'getMaster.getCustomer', 'getTruckDriver.getUser']);
        $truck = Truck::get();

        if ($request->truck) {
            $data->whereRelation('getTruckDriver.getTruck', 'id', '=', $request->truck);
            $data = $data->orderBy('created_at', 'DESC')->paginate(10);
        } else {
            $data = [];
        }

        return view('transaksi.suratjalan.index', compact('data', 'truck'));
    }

    public function laporsj($so, $truck)
    {
        $truckDriver = TruckDriver::where('truck_no_polis', $truck)->where('truck_is_active', 1)->firstOrFail();
        $idTruckDriver = $truckDriver->id ?? 0;
        $data = SalesOrderSangu::with(['getTruckDriver.getTruck','getMaster.getDetail',
            'countLaporanHist.getTruckDriver.getTruck',
            'countLaporanHist.getTruckDriver.getUser'])
            ->with('countLaporanHist', function ($query) use ($idTruckDriver) {
                $query->where('soh_driver', '=', $idTruckDriver);
            })
            ->where('sos_so_mstr_id', $so)
            ->whereRelation('getTruckDriver.getTruck', 'id', '=', $truck)
            ->firstOrFail();
        return view('transaksi.suratjalan.laporsj', compact('data'));
    }

    public function updatesj(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try{
            // Kirim Qxtend
            $soship = (new QxtendServices())->qxSOShip($request->all());
            if($soship === false || $soship[0] == 'error'){
                alert()->error('Error', 'Save Gagal, Error Qxtend')->persistent('Dismiss');
                return back();
            }
            if($soship == 'nourl'){
                alert()->error('Error', 'Url Qxtend Belum disetup')->persistent('Dismiss');
                return back();
            }
            // Update Mstr
            $somstr = SalesOrderMstr::findOrFail($request->idmstr);
            $somstr->so_remark = $request->remark;
            $somstr->so_effdate = $request->effdate;


            $totalship = 0;
            // Save Qty Ship & Update Detail
            foreach($request->iddetail as $keys => $iddetail){
                $sodetail = SalesOrderDetail::findOrFail($iddetail);
                $totalship = $sodetail->sod_qty_ship + $request->qtyakui[$keys];
                
                $sodetail->sod_qty_ship = $totalship;
                $sodetail->save();
            }
            $cekdetail = SalesOrderDetail::query()
                                    ->where('sod_so_mstr_id',$request->idmstr)
                                    ->whereRaw('sod_qty_ord > sod_qty_ship')
                                    ->first();
            if(!$cekdetail){
                $somstr->so_status = 'Closed';
                $somstr->save();
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

    public function catatsj($so, $truck)
    {
        $truckDriver = TruckDriver::where('truck_no_polis', $truck)->where('truck_is_active', 1)->firstOrFail();
        $idTruckDriver = $truckDriver->id ?? 0;
        
        $data = SalesOrderSangu::with(['getTruckDriver.getTruck','getMaster.getDetail',
            'countLaporanHist.getTruckDriver.getTruck',
            'countLaporanHist.getTruckDriver.getUser'])
            ->with('countLaporanHist', function ($query) use ($idTruckDriver) {
                $query->where('soh_driver', '=', $idTruckDriver);
            })
            ->where('sos_so_mstr_id', $so)
            ->whereRelation('getTruckDriver.getTruck', 'id', '=', $truck)
            ->firstOrFail();
        return view('transaksi.suratjalan.catatsj.index', compact('data'));
    }

    public function updatecatatsj(Request $request)
    {
        DB::beginTransaction();
        try{
            // Ubah Status Sangu
            $sosangu = SalesOrderSangu::findOrFail($request->idsangu[0]);
            $histtrip = SOHistTrip::query()
                                  ->where('soh_so_mstr_id',$sosangu->sos_so_mstr_id)
                                  ->where('soh_driver',$sosangu->sos_truck)
                                  ->where('soh_sj','!=','')
                                  ->get();
            $totalhisttrip = $histtrip->count();
            
            if($sosangu->sos_tot_trip == $totalhisttrip){
                $sosangu->so_status = 'Closed';
                $sosangu->save();
            }
            

            // Save SJ
            foreach($request->idhist as $key => $idhist){
                $sohist = SOHistTrip::findOrFail($idhist);
                $sohist->soh_sj = $request->sj[$key];
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
