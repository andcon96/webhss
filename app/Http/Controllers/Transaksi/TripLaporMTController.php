<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SJHistTrip;
use App\Models\Transaksi\SuratJalan;
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
                               'getSOMaster.getShipTo',
                               'getHistTrip')
                        ->where(function($query){
                                $query->where('sj_status','Selesai');
                                $query->orWhere('sj_status','Open');
                        });

        $userid = Auth::user()->id;
        $userDriver = Truck::where('truck_user_id',$userid)->first();


        if ($request->truck) {
            $data->whereRelation('getTruck', 'id', $request->truck);
            $data = $data->orderBy('created_at', 'DESC')->get();
        } else {
            if ($userDriver) {
                $data = SuratJalan::with(['getSOMaster','getTruck',
                                    'getSOMaster.getShipTo',])
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
        $data = SuratJalan::with('getDetail.getItem')
                    ->with('getHistTrip', function ($query) {
                        $query->groupBy('sjh_truck');
                    })
                    ->findOrFail($id);
     
        $listdriver = SuratJalan::with(['getTruck.getUserDriver'])
                                ->where('id',$id)
                                ->get();
                    
        $sohbyso = SJHistTrip::query()->with(['getSJMaster','getTruck.getUserDriver']);

        if (Auth::user()->role_id != '1') {
            $sohbyso->whereRelation('getTruck.getUserDriver', 'id', '=', Auth::id());
        }

        $sohbyso = $sohbyso->where('sjh_sj_mstr_id', $id)
                           ->select('*','sj_trip_hist.created_at as tglhist')
                           ->orderBy('sjh_truck', 'ASC')
                           ->get();
                           
        return view('transaksi.trip.lapor.edit', compact('data', 'sohbyso', 'listdriver'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user()->id;
            
            $truck = Truck::query()
                            ->where('truck_user_id', $user)
                            ->where('truck_is_active',1)
                            ->firstOrFail();

            $sjmstr = SuratJalan::findOrFail($request->idsjmaster);
                
            $targetAbsen = $sjmstr->sj_jmlh_trip;

            $OnGoingAbsen = SJHistTrip::where('sjh_sj_mstr_id', $request->idsjmaster)
                                        ->where('sjh_truck', $truck->id)
                                        ->count();

            if ($OnGoingAbsen >= $targetAbsen) {
                alert()->error('Error', 'Target Absensi Sudah Tercapai, Data tidak disimpan')->persistent('Dismiss');
                return back();
            }

            $newdata = new SJHistTrip();
            $newdata->sjh_sj_mstr_id = $request->idsjmaster;
            $newdata->sjh_truck = $truck->id;
            $newdata->save();

            if($OnGoingAbsen + 1 == $targetAbsen){
                $sjmstr->sj_status = 'Selesai';
                $sjmstr->save();

                // $sisaSJ = SuratJalan::where('sj_so_mstr_id', $request->idsomaster)
                //                               ->where('sj_status','Open')
                //                               ->count();
                // if($sisaSJ == 0){
                //     $so_mstr = SalesOrderMstr::find($request->idsomaster);
                //     if($so_mstr->so_status != 'Closed' && $so_mstr->so_status != 'Cancelled'){
                //         $so_mstr->so_status = 'Selesai';
                //         $so_mstr->save();
                //     }
                // }
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
