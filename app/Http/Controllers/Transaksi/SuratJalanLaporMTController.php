<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\BonusBarang;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\InvoicePrice;
use App\Models\Master\ShipFrom;
use App\Models\Master\Truck;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SJHistTrip;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\QxtendServices;
use App\Services\WSAServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratJalanLaporMTController extends Controller
{
    public function index(Request $request)
    {
        $data = SuratJalan::query()
                        ->with('getTruck.getUserDriver',
                               'getSOMaster.getCOMaster.getCustomer',
                               'getSOMaster.getShipFrom',
                               'getSOMaster.getShipTo');
                               
        $truck = Truck::get();
        $customer = Customer::get();
        $shipto = CustomerShipTo::get();
        $shipfrom = ShipFrom::get();

        if($request->customer){
            $data->whereRelation('getSOMaster.getCOMaster','co_cust_code',$request->customer);
        }

        if($request->shipfrom){
            $data->whereRelation('getSOMaster','so_ship_from',$request->shipfrom);
        }

        if($request->shipto){
            $data->whereRelation('getSOMaster','so_ship_to',$request->shipto);
        }

        if($request->kapal){
            $data->whereRelation('getSOMaster.getCOMaster','co_kapal','like', '%'.$request->kapal.'%');
        }

        if($request->status){
            $data->where('sj_status','=',$request->status);
        }

        if ($request->truck) {
            $data->where('sj_truck_id', $request->truck);
        }
        
        if(!$request->customer && !$request->shipfrom && 
           !$request->shipto && !$request->kapal && 
           !$request->status && !$request->truck){

            $data = $data->where('id',0)->paginate(10);
        }else{
            $data = $data->orderBy('created_at', 'DESC')->paginate(10);
        }


        return view('transaksi.sjcust.index', compact('data', 'truck','customer','shipto','shipfrom'));
    }

    public function laporsj($sj, $truck)
    {
        $data = SuratJalan::query()
                        ->with('getTruck.getUserDriver',
                               'getDetail.getItem',
                               'getSOMaster.getCOMaster.getCustomer',
                               'getSOMaster.getShipTo',
                               'getSOMaster.getShipFrom',
                                )
                        ->where('id',$sj)
                        ->where('sj_truck_id',$truck)
                        ->firstOrFail();
        
        $invoiceprice = InvoicePrice::query()
                        ->with('getAllActivePrice')
                        ->where('ip_customership_id', $data->getSOMaster->getShipTo->id)
                        ->where('ip_shipfrom_id', $data->getSOMaster->getShipFrom->id)
                        ->where('ip_cust_id', $data->getSOMaster->getCOMaster->getCustomer->id)
                        ->get();
                        
        
        return view('transaksi.sjcust.laporsj', compact('data','truck','invoiceprice'));
    }

    public function updatesj(Request $request)
    {
        DB::beginTransaction();
        try{
            // Update Master
            $sjmstr = SuratJalan::with('getSOMaster.getCOMaster')->findOrFail($request->idsjmstr);
            $sjmstr->sj_conf_remark = $request->remark;
            $sjmstr->sj_conf_date = $request->effdate;
            $sjmstr->sj_status = 'Closed';

            // Update Detail
            $totalkirim = 0;
            foreach($request->iddetail as $keys => $iddetail){
                $sjddet = SuratJalanDetail::findOrFail($iddetail);
                $sjddet->sjd_qty_angkut = $request->qtyangkut[$keys];
                $sjddet->sjd_price = str_replace(',','',$request->price[$keys]);
                $sjddet->sjd_qty_conf = $sjddet->sjd_qty_conf + $request->qtyakui[$keys];
                $sjddet->save();

                $totalkirim += $request->qtyangkut[$keys];
            }
            $floor_totalkirim = floor($totalkirim / 1000) * 1000;

            // Get Truck
            $truck = Truck::findOrFail($sjmstr->sj_truck_id);

            // Cek Bonus 
            $bonus = BonusBarang::query()
                        ->where('bb_qty_start','<=',$floor_totalkirim)
                        ->where('bb_qty_end','>=',$floor_totalkirim)
                        ->where('bb_tipe_truck_id',$truck->truck_tipe_id)
                        ->where('bb_barang_id',$sjmstr->getSOMaster->getCOMaster->co_barang_id)
                        ->where('bb_is_active',1)
                        ->first();
            
            $sjmstr->sj_bb_id = $bonus->id ?? null;
            $sjmstr->save();

            // Get SO Mstr
            $somstr = SalesOrderMstr::query()
                        ->with('getDetail' ,function($q){
                            $q->whereRaw('sod_qty_ord > sod_qty_ship');   
                        })
                        ->with('getOpenOrSelesaiSJ')
                        ->find($sjmstr->sj_so_mstr_id);
            $soddet = $somstr->getDetail->count(); // 0 => Semua detail full ship
            $listsj = $somstr->getOpenOrSelesaiSJ->count(); // 0 => Semua SJ antara Closed / Cancelled

            if($soddet == 0 && $listsj == 0){
                $somstr->so_status = 'Closed';
                $somstr->save();
            }
            
            // Kirim Qxtend
            $pendinginvoice = (new QxtendServices())->qxPendingInvoice($request->all());
            if($pendinginvoice === false){
                alert()->error('Error', 'Error Qxtend, Silahkan cek URL Qxtend.')->persistent('Dismiss');
                DB::rollback();
                return back();
            }elseif($pendinginvoice == 'nourl'){
                alert()->error('Error', 'Mohon isi URL Qxtend di Setting QXWSA.')->persistent('Dismiss');
                DB::rollback();
                return back();
            }elseif($pendinginvoice[0] == 'error'){
                alert()->error('Error', 'Qxtend kembalikan error, Silahkan cek log Qxtend')->persistent('Dismiss');
                DB::rollback();
                return back();
            }
            
            DB::commit();
            alert()->success('Success', 'Surat Jalan Berhasil Disimpan')->persistent('Dismiss');
            return redirect()->route('laporsj.index');
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
                        
        return view('transaksi.sjcust.catatsj.index', compact('data','truck'));
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
