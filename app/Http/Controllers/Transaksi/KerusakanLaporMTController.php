<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\Kerusakan;
use App\Models\Master\KerusakanStruktur;
use App\Models\Master\KerusakanStrukturDetail;
use App\Models\Master\StrukturKerusakan;
use App\Models\Master\Truck;
use App\Models\Transaksi\KerusakanDetail;
use App\Models\Transaksi\KerusakanMstr;
use App\Services\CreateTempTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;

class KerusakanLaporMTController extends Controller
{
    public function index(Request $request)
    {

        $data = KerusakanMstr::query()
            ->with(['getDetail', 'getTruck','getTruck.getUserDriver']);
        
        if ($request->s_krnbr) {
            $data->where('kerusakan_nbr', $request->s_krnbr);
        }
        if ($request->s_driver) {
            $data->whereRelation('getTruck', 'id', '=', $request->s_driver);
        }
        
        // $data->where('kerusakan_domain',Session::get('domain'));

        $data = $data->orderBy('created_at', 'DESC')->paginate(10);
        
        $truck = Truck::get();

        return view('transaksi.kerusakan.index', compact('data', 'truck'));
    }

    public function show($id)
    {
        $data = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver','getDetail.getStrukturTrans','getDetail.getKerusakan'])->findOrFail($id);
        $jeniskerusakan = Kerusakan::get();
        $struktur = KerusakanStruktur::get();
        // dd($data->getMekanik);
        return view('transaksi.kerusakan.show', compact('data', 'jeniskerusakan', 'struktur'));
    }

    public function create()
    {
        // $truckdriver = TruckDriver::with(['getTruck', 'getUser'])->where('truck_is_active', 1)->get();
        $jeniskerusakan = Kerusakan::get();
        $truck = Truck::get();
        return view('transaksi.kerusakan.create', compact( 'jeniskerusakan','truck'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $getrn = (new CreateTempTable())->getrnkerusakan();
            
            if ($getrn === false) {
                alert()->error('Error', 'Gagal Melaporkan Kerusakan');
                DB::rollBack();
                return back();
            }
            // dd($request->all());
            $kerusakan_mstr = new KerusakanMstr();
            $kerusakan_mstr->kr_nbr = $getrn;
            $kerusakan_mstr->kr_truck = $request->truck;
            $kerusakan_mstr->kr_date = $request->tgllapor;
            $kerusakan_mstr->kr_status = 'New';
            $kerusakan_mstr->kr_domain = Session::get('domain');
            $kerusakan_mstr->save();

            $id = $kerusakan_mstr->id;
            foreach ($request->jeniskerusakan as $key => $datas) {
                $kerusakan_detail = new KerusakanDetail();
                $kerusakan_detail->krd_kr_mstr_id = $id;
                $kerusakan_detail->krd_kerusakan_id = $datas;
                $kerusakan_detail->save();
            }

            // $prefix = Prefix::firstOrFail();
            $prefix = Domain::where('domain_code',Session::get('domain'))->firstOrFail();
            $prefix->domain_kr_rn = substr($getrn, 2, 6);
            $prefix->save();

            DB::commit();
            alert()->success('Success', 'Kerusakan berhasil dilaporkan')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            alert()->error('Error', 'Failed to create data')->persistent('Dismiss');
            return back();
        }
    }

    public function edit($id)
    {
        $data = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver'])->findOrFail($id);
        $jeniskerusakan = Kerusakan::get();

        return view('transaksi.kerusakan.edit', compact('data', 'jeniskerusakan'));
    }

    public function update(Request $request)
    {
        

        DB::beginTransaction();
        try {
            foreach ($request->iddetail as $key => $datas) {
                $detail = KerusakanDetail::firstOrNew(['id' => $datas]);
                
                if ($request->operation[$key] == 'R') {
                    $detail->delete();
                } else {
                    
                    $detail->krd_kr_mstr_id = $request->idmaster;
                    $detail->krd_kerusakan_id = $request->jeniskerusakan[$key];

                    $detail->save();
                }
                
            }

            DB::commit();
            alert()->success('Success', 'Kerusakan berhasil di update')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            alert()->error('Error', 'Update Gagal')->persistent('Dismiss');
            return back();
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = KerusakanMstr::findOrFail($request->temp_id);
            $data->kerusakan_status = 'Cancelled';
            $data->save();

            DB::commit();
            alert()->success('Success', 'Kerusakan berhasil dicancel')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Error', 'Cancel Gagal')->persistent('Dismiss');
            return back();
        }
    }

    public function assignkr($id)
    {
        $data = KerusakanMstr::with(['getDetail.getKerusakan', 'getTruck', 'getTruck.getUserDriver','getDetail.getStrukturTrans'])->findOrFail($id);
        $jeniskerusakan = Kerusakan::get();
        $struktur = KerusakanStruktur::get();

        return view('transaksi.kerusakan.assignkr', compact('data', 'jeniskerusakan', 'struktur'));
    }

    public function upassignkr($id, Request $request)
    {
        dd($request->all());
        DB::beginTransaction();
        try {
            // Update Detail
            foreach ($request->iddetail as $key => $datas) {
                $detail = KerusakanDetail::firstOrNew(['id' => $datas]);
                if ($request->operation[$key] == 'R') {
                    $detail->delete();
                } else {
                    $detail->kerusakan_mstr_id = $request->idmaster;
                    $detail->kerusakan_id = $request->jeniskerusakan[$key];
                    $detail->save();
                }
            }

            // Assign Mekanik
            foreach ($request->struk_mekanik_id as $keys => $datas) {
                $struktur_detail = KerusakanStrukturDetail::updateOrCreate([
                    'kerusakan_struktur_id' => $datas,
                    'kerusakan_mstr_id' => $request->idmaster,
                    'kerusakan_struktur_detail_id' => $request->struk_detail_id[$keys]
                ]);

                $struktur_detail->kerusakan_mekanik = $request->struk_desc[$keys];
                $struktur_detail->save();
            }

            // Update Status Mstr
            $krmstr = KerusakanMstr::findOrFail($request->idmaster);
            $krmstr->kerusakan_status = 'Ongoing';
            $krmstr->save();
            
            DB::commit();
            alert()->success('Success', 'Kerusakan berhasil dilaporkan')->persistent('Dismiss');
            return redirect()->route('laporkerusakan.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Error', 'Update Gagal')->persistent('Dismiss');
            return back();
        }
    }
}
