<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\InvoicePrice;
use App\Models\Master\Rute;
use App\Models\Master\ShipFrom;
use App\Models\Master\Truck;
use App\Models\Transaksi\SalesOrderDetail;
use App\Models\Transaksi\SalesOrderMstr;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\QxtendServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfirmSuratJalanController extends Controller
{
    public function index(Request $request)
    {
        $listsj = SuratJalan::where('sj_status', 'Selesai')->get();
        $listso = SalesOrderMstr::get();
        $listcust = Customer::get();
        $listtruck = Truck::get();
        $listshipfrom = ShipFrom::get();
        $listshipto = CustomerShipTo::get();

        $data = SuratJalan::query()->with(['getSOMaster.getCOMaster.getCustomer', 'getTruck', 'getSOMaster.getShipFrom', 'getSOMaster.getShipTo'])->where('sj_status', 'Selesai');

        if ($request->sonumber) {
            $data->whereRelation('getSOMaster', 'id', $request->sonumber);
        }
        if ($request->sjnumber) {
            $data->where('id', $request->sjnumber);
        }
        if ($request->customer) {
            $data->whereRelation('getSOMaster.getCOMaster', 'co_cust_code', $request->customer);
        }
        if ($request->truck) {
            $data->where('sj_truck_id', $request->truck);
        }
        if ($request->shipfrom) {
            $data->whereRelation('getSOMaster', 'so_ship_from', $request->shipfrom);
        }
        if ($request->shipto) {
            $data->whereRelation('getSOMaster', 'so_ship_to', $request->shipto);
        }


        $data = $data->paginate(10);

        return view('transaksi.confirmsj.index', compact('data', 'listsj', 'listso', 'listcust', 'listtruck', 'listshipfrom', 'listshipto'));
    }

    public function confirmsj($sj, $truck)
    {
        $data = SuratJalan::query()
            ->with(
                'getTruck.getUserDriver',
                'getDetail.getItem',
                'getSOMaster.getCOMaster.getCustomer',
                'getSOMaster.getShipTo',
                'getSOMaster.getShipFrom',
                'getSOMaster.getDetail',
                'getRuteHistory'
            )
            ->where('id', $sj)
            ->where('sj_truck_id', $truck)
            ->firstOrFail();

        $invoiceprice = InvoicePrice::query()
            ->with('getAllActivePrice')
            ->where('ip_customership_id', $data->getSOMaster->getShipTo->id)
            ->where('ip_shipfrom_id', $data->getSOMaster->getShipFrom->id)
            ->where('ip_cust_id', $data->getSOMaster->getCOMaster->getCustomer->id)
            ->get();

        $rute = Rute::query()
            ->where('rute_tipe_id', $data->getTruck->truck_tipe_id)
            ->where('rute_shipfrom_id', $data->getSOMaster->getShipFrom->id)
            ->where('rute_customership_id', $data->getSOMaster->getShipTo->id)
            ->with('getAllActivePrice')
            ->first();

        // dd($invoiceprice);
        return view('transaksi.confirmsj.confirm', compact('data', 'truck', 'invoiceprice', 'rute'));
    }

    public function saveconfirmsj(Request $request)
    {
        DB::beginTransaction();
        try {
            $sjmstr = SuratJalan::findOrFail($request->idsjmstr);
            $sjmstr->sj_status = 'Closed';

            // SJ Detail -> Save Price ke Detail
            foreach ($request->iddetail as $keys => $iddetail) {
                $sjddet = SuratJalanDetail::findOrFail($iddetail);
                $sjddet->sjd_price = str_replace(',', '', $request->price[$keys]);
                $sjddet->save();
            }


            // Qxtend
            if ($sjmstr->sj_sent_qad == 0) {
                $pendinginvoice = (new QxtendServices())->qxPendingInvoice($request->all());
                if ($pendinginvoice === false) {
                    alert()->error('Error', 'Error Qxtend, Silahkan cek URL Qxtend.')->persistent('Dismiss');
                    DB::rollback();
                    return back();
                } elseif ($pendinginvoice == 'nourl') {
                    alert()->error('Error', 'Mohon isi URL Qxtend di Setting QXWSA.')->persistent('Dismiss');
                    DB::rollback();
                    return back();
                } elseif ($pendinginvoice[0] == 'error') {
                    alert()->error('Error', 'Qxtend kembalikan error, Silahkan cek log Qxtend')->persistent('Dismiss');
                    DB::rollback();
                    return back();
                } else {
                    $sjmstr->sj_sent_qad = 1;
                }
            }

            $sjmstr->save();

            DB::commit();
            alert()->success('Success', 'Update & Qxtend berhasil')->persistent('Dismiss');
            return redirect()->to($request->prevurl ?? url()->previous());
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Error', 'Surat Jalan gagal diupdate')->persistent('Dismiss');
            return back();
        }
    }
}
