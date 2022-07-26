<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\InvoicePrice;
use App\Models\Master\InvoicePriceHistory;
use App\Models\Master\ShipFrom;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicePriceController extends Controller
{
    public function index(Request $request)
    {
        $customer = Customer::query();
        $listcust = Customer::all();

        if ($request->s_custcode) {
            $customer->where('id', $request->s_custcode);
        }

        $customer = $customer->paginate(10);

        return view('setting.invoice-price.index', compact('customer', 'listcust'));
    }

    public function store(Request $request)
    {
        $ruteid = $request->idrute;
        $tonaseprice = str_replace(',', '', $request->tonaseprice);
        $ritsprice = str_replace(',', '', $request->ritsprice);
        $lasthistory = InvoicePriceHistory::where('iph_ip_id', $ruteid)->where('iph_is_active', 1)->first();
        DB::beginTransaction();

        try {
            if ($lasthistory) {
                InvoicePriceHistory::where('iph_ip_id', $ruteid)
                    ->where('iph_is_active', 1)
                    ->update([
                        'iph_is_active' => 0,
                        'iph_last_active' => Carbon::now()->toDateTimeString(),
                    ]);
            }
            $rutehist = new InvoicePriceHistory();
            $rutehist->iph_ip_id = $ruteid;
            $rutehist->iph_tonase_price = $tonaseprice;
            $rutehist->iph_trip_price = $ritsprice;
            $rutehist->iph_is_active = 1;
            $rutehist->save();
            DB::commit();
            alert()->success('Success', 'History berhasil diperbarui');
            return back();
        } catch (Exception $err) {
            DB::rollback();
            dd($err);
            alert()->error('Error', 'Terjadi kesalahan');
            return back();
        }
    }

    public function listdetail(Request $request, $id)
    {
        // dd($id);
        $invoiceprice = InvoicePrice::with(['getShipFrom', 'getShipTo', 'getCustomer'])->where('ip_cust_id', $id);

        if ($request->s_shipfrom) {
            $invoiceprice->where('ip_shipfrom_id', $request->s_shipfrom);
        }
        if ($request->s_shipto) {
            $invoiceprice->where('ip_customership_id', $request->s_shipto);
        }

        $invoiceprice = $invoiceprice->paginate(10);

        $shipfrom = ShipFrom::get();
        $shipto = CustomerShipTo::get();

        $id = $id;

        return view('setting.invoice-price.indexdetail', compact('invoiceprice', 'shipfrom', 'shipto', 'id'));
    }

    public function newinvoiceprice(Request $request)
    {
        $cekrute = InvoicePrice::query()
            ->where('ip_cust_id', $request->tipecode)
            ->where('ip_shipfrom_id', $request->shipfrom)
            ->where('ip_customership_id', $request->shipto)
            ->first();
        if ($cekrute) {
            alert()->error('Error', 'Data Exist');
            return back();
        }

        DB::beginTransaction();
        try {
            $rute = new InvoicePrice();
            $rute->ip_cust_id = $request->tipecode;
            $rute->ip_shipfrom_id = $request->shipfrom;
            $rute->ip_customership_id = $request->shipto;
            $rute->save();
            DB::commit();
            alert()->success('Success', 'Data berhasil di tambah');
            return back();
        } catch (Exception $err) {
            DB::rollback();
            alert()->error('Error', 'Data gagal di tambah');
            return back();
        }
    }

    public function viewhistory($detailid, $id)
    {
        $history_data = InvoicePriceHistory::query()
            ->with(['getIP.getShipTo', 'getIP.getShipFrom', 'getIP.getCustomer'])
            ->where('iph_ip_id', $id)
            ->orderBy('iph_is_active', 'desc')
            ->orderBy('iph_last_active', 'desc')
            ->get();
        $rute = InvoicePrice::with('getShipFrom', 'getCustomer', 'getShipTo')->where('id', $id)->first();
        $id = $detailid;

        return view('setting.invoice-price.indexhistory', compact('history_data', 'rute', 'id'));
    }
}
