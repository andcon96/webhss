<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SalesOrderSangu;
use App\Models\Transaksi\SOHistTrip;
use App\Models\Transaksi\SuratJalan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripLaporMTController extends Controller
{
    public function index(Request $request)
    {
        $truck = Truck::orderBy('truck_no_polis', 'ASC')->get();

        $data = SuratJalan::query()
                        ->with('getTruck',
                               'getSOMaster.getCOMaster.getCustomer',
                               'getHistTrip');

        $userid = Auth::user()->id;
        $userDriver = Truck::where('truck_user_id',$userid)->first();


        if ($request->truck) {
            $data->whereRelation('getTruck', 'id', $request->truck);
            $data = $data->orderBy('created_at', 'DESC')->get();
        } else {
            if ($userDriver) {
                $data = SuratJalan::with(['getSOMaster','getTruck'])
                                  ->whereRelation('getTruck','id',$userDriver->id)
                                  ->where('sj_status','!=','Closed')
                                  ->where(function($query){
                                        $query->where('sj_status','Selesai');
                                        $query->orWhere('sj_status','Open');
                                  })
                                  ->orderBy('created_at','DESC')
                                  ->get();
            } else {
                $data = [];
            }
        }

        return view('transaksi.trip.lapor.index', compact('truck', 'data', 'userDriver'));
    }

    public function edit($id)
    {
        $data = SalesOrderMstr::with('getDetail')
            ->with('getSangu.countLaporanHist', function ($query) {
                $query->groupBy('soh_driver');
            })
            ->findOrFail($id);

        $listdriver = SalesOrderSangu::with(['getTruckDriver.getUser','getTruckDriver.getTruck'])
                    ->where('sos_so_mstr_id', $id)
                    ->get();
                    
        $sohbyso = SOHistTrip::query()
            ->with(['getMaster', 'getTruckDriver.getUser', 'getTruckDriver.getTruck'])
            ->join('so_sangu', function ($e) {
                $e->on('so_hist_trip.soh_so_mstr_id', 'so_sangu.sos_so_mstr_id');
                $e->on('so_hist_trip.soh_driver', 'so_sangu.sos_truck');
            });

        if (Auth::user()->role_id != '1') {
            $sohbyso->whereRelation('getTruckDriver.getUser', 'id', '=', Auth::id());
        }

        $sohbyso = $sohbyso->where('soh_so_mstr_id', $id)
                           ->select('*','so_hist_trip.created_at as tglhist')
                           ->orderBy('soh_driver', 'ASC')
                           ->get();
        
        return view('transaksi.trip.lapor.edit', compact('data', 'sohbyso', 'listdriver'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user()->id;
            $truckDriver = TruckDriver::where('truck_user_id', $user)
                                      ->where('truck_is_active',1)
                                      ->firstOrFail();
            $SOSangu = SalesOrderSangu::where('sos_so_mstr_id', $request->idmaster)
                ->where('sos_truck', $truckDriver->id)
                ->first();
                
            $targetAbsen = $SOSangu->sos_tot_trip;

            $OnGoingAbsen = SOHistTrip::where('soh_so_mstr_id', $request->idmaster)
                ->where('soh_driver', $truckDriver->id)
                ->count();

            if ($OnGoingAbsen >= $targetAbsen) {
                alert()->error('Error', 'Target Absensi Sudah Tercapai, Data tidak disimpan')->persistent('Dismiss');
                return back();
            }

            $newdata = new SOHistTrip();
            $newdata->soh_so_mstr_id = $request->idmaster;
            $newdata->soh_driver = $truckDriver->id;
            $newdata->created_at = Carbon::now();
            $newdata->save();

            if($OnGoingAbsen + 1 == $targetAbsen){
                $SOSangu->so_status = 'Selesai';
                $SOSangu->save();
                
                $sisaSOSangu = SalesOrderSangu::where('sos_so_mstr_id', $request->idmaster)
                                              ->where('so_status','Open')
                                              ->count();
                if($sisaSOSangu == 0){
                    $so_mstr = SalesOrderMstr::find($request->idmaster);
                    if($so_mstr->so_status != 'Closed' && $so_mstr->so_status != 'Cancelled'){
                        $so_mstr->so_status = 'Selesai';
                        $so_mstr->save();
                    }
                }
            }

            DB::commit();
            alert()->success('Success', 'Absensi Berhasil Disimpan')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Error', 'Save Gagal silahkan dicoba berberapa saat lagi')->persistent('Dismiss');
            return back();
        }
    }
}
