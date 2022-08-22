<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\ListRBHist;
use App\Models\Master\Truck;
use App\Models\Transaksi\ReportBiaya;
use App\Models\Transaksi\ReportBiayaDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportBiayaController extends Controller
{
    
    public function index(Request $request)
    {
        $truck = Truck::get();

        $data = ReportBiaya::query()->with('getTruck','getDetail');

        if($request->truck){
            $data->where('rb_truck_id',$request->truck);
        }

        if($request->datefrom){
            $data->where('rb_eff_date','>=',$request->datefrom);
        }

        if($request->dateto){
            $data->where('rb_eff_date','<=',$request->dateto);
        }

        $data = $data->orderBy('created_at','desc')->paginate(10);

        return view('transaksi.rbhist.index',compact('data','truck'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $truck = Truck::get();
        $listrbhist = ListRBHist::get();

        return view('transaksi.rbhist.create',compact('truck','listrbhist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $rbhist = new ReportBiaya();
            $rbhist->rb_truck_id = $request->truck;
            $rbhist->rb_eff_date = $request->effdate;
            // $rbhist->rb_nominal = str_replace(',','',$request->nominal);
            $rbhist->rb_remark = $request->remark;
            $rbhist->rb_is_pemasukan = $request->tipe;
            $rbhist->save();

            $idmaster = $rbhist->id;
            $total = 0;
            foreach($request->deskripsi as $keys => $datas){
                if($request->nominal[$keys] > 0){
                    $rbdetail = new ReportBiayaDetail();
                    $rbdetail->rbd_rb_hist_id = $idmaster;
                    $rbdetail->rbd_deskripsi = $datas;
                    $rbdetail->rbd_nominal = str_replace(',','',$request->nominal[$keys]);
                    $rbdetail->save();
                    $total += str_replace(',','',$request->nominal[$keys]);
                }
            }
            
            $updateharga = ReportBiaya::findOrFail($idmaster);
            $updateharga->rb_nominal = $total;
            $updateharga->save();

            DB::commit();
            alert()->success('Success', 'Save Berhasil')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Save Gagal')->persistent('Dismiss');
            return back();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi\ReportBiaya  $reportBiaya
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportBiaya $reportBiaya,$id)
    {
        $rbhist = ReportBiaya::with(['getDetail','getTruck'])->findOrFail($id);

        return view('transaksi.rbhist.edit',compact('rbhist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi\ReportBiaya  $reportBiaya
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $rbhist = ReportBiaya::findOrFail($id);
            $rbhist->rb_eff_date = $request->effdate;
            $rbhist->rb_remark = $request->remark;

            $total = 0;
            foreach($request->iddeskripsi as $keys => $datas){
                if($request->nominal[$keys] > 0){
                    $rbdetail = ReportBiayaDetail::firstOrNew(['id' => $datas]);
                    $rbdetail->rbd_rb_hist_id = $id;
                    $rbdetail->rbd_deskripsi = $request->deskripsi[$keys];
                    $rbdetail->rbd_nominal = str_replace(',','',$request->nominal[$keys]);
                    $rbdetail->save();
                    $total += str_replace(',','',$request->nominal[$keys]);
                }
            }

            $rbhist->rb_nominal = $total;
            $rbhist->save();

            DB::commit();
            alert()->success('Success', 'Save Berhasil')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Save Gagal')->persistent('Dismiss');
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi\ReportBiaya  $reportBiaya
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $data = ReportBiaya::findOrFail($id);
        $data->rb_is_active = 0;
        $data->save();

        alert()->success('Success', 'Delete Berhasil')->persistent('Dismiss');
        return back();

    }
}
