<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\BankCustomer;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Domain;
use App\Models\Master\InvoicePrice;
use App\Models\Master\InvoicePriceHistory;
use App\Models\Master\Prefix;
use App\Models\Master\ShipFrom;
use App\Models\Transaksi\InvoiceMaster;
use App\Models\Transaksi\InvoiceDetail;
use App\Models\Transaksi\SalesOrderMstr;
use App\Services\CreateTempTable;
use App\Services\WSAServices;
use Carbon\Carbon;
use PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceMTController extends Controller
{
    public function index()
    {
        $list_invoice = InvoiceMaster::with('getDetail')->get();
        $list_sonbr = SalesOrderMstr::all();
        $data = InvoiceMaster::with('getDetail', 'getSalesOrder')->orderBy('id', 'DESC')->paginate(10);

        return view('transaksi.invoice.index', compact('data', 'list_invoice', 'list_sonbr'));
    }

    public function edit($id)
    {
        $data = InvoiceMaster::with('getDetail')->findOrFail($id);

        $listdomain = Domain::get();

        return view('transaksi.invoice.edit', compact('data', 'listdomain'));
    }

    public function create()
    {
        $list_sonbr = SalesOrderMstr::all();
        $list_domain = Domain::all();
        return view('transaksi.invoice.create', compact('list_sonbr', 'list_domain'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $prefix = Prefix::lockForUpdate()->first();

            $getIV = (new CreateTempTable())->getrniv(); // Isi Array [0] Invoice, Array [1] Running Number

            if ($getIV === false) {
                alert()->error('Error', 'Gagal mengambil nomor SJ')->persistent('Dismiss');
                return back();
            }

            $invmstr = new InvoiceMaster();
            $invmstr->im_nbr = $getIV[0];
            $invmstr->im_so_mstr_id = $request->sonbr;
            $invmstr->im_date = $request->effdate;
            $invmstr->save();

            $invmstr_id = $invmstr->id;
            foreach ($request->domain as $key => $datas) {
                $invdet = new InvoiceDetail();
                $invdet->id_im_mstr_id = $invmstr_id;
                $invdet->id_domain = $datas;
                $invdet->id_duedate = $request->duedate[$key];
                $invdet->id_nbr = $request->ivnbr[$key];
                $invdet->id_total = str_replace(',', '', $request->price[$key]);
                $invdet->save();
            }

            $prefix->prefix_iv_rn = $getIV[1];
            $prefix->save();

            DB::commit();
            alert()->success('Success', 'Save Berhasil')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Error', 'Data gagal disimpan')->persistent('Dismiss');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $invmstr = InvoiceMaster::findOrFail($id);
            foreach ($request->iddetail as $keys => $iddetail) {
                $invdet = InvoiceDetail::firstOrNew([
                    'id' => $iddetail
                ]);
                if ($request->operation[$keys] == 'R') {
                    $invdet->delete();
                } else {
                    $invdet->id_im_mstr_id = $id;
                    $invdet->id_domain = $request->domain[$keys];
                    $invdet->id_nbr = $request->invnbr[$keys];
                    $invdet->id_duedate = $request->duedate[$keys];
                    $invdet->id_total = str_replace(',', '', $request->total[$keys]);
                    $invdet->save();
                }
            }

            DB::commit();
            alert()->success('Success', 'Save Berhasil')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            alert()->success('Error', 'Error, Failed to save')->persistent('Dismiss');
            return back();
        }
    }

    public function checkInvoice(Request $request)
    {
        if ($request->ajax()) {
            $checkData = (new WSAServices())->wsacheckinvoice($request->domain, $request->invoiceqad);

            if ($checkData === false) {
                return response()->json(['error' => 'WSA Failed'], 404);
            }

            return [number_format((float)$checkData[0], 2), $checkData[1]];
        }
    }

    // Invoice Web
    public function printinvoice($id)
    {
        $data = InvoiceMaster::with([
            'getDetail.getDomain',
            'getDetail',
            'getSalesOrder.getCOMaster.getCustomer'
        ])->findOrFail($id);


        $bankacc = BankCustomer::where('bc_customer_id', $data->getSalesOrder->getCOMaster->getCustomer->id ?? '')
            ->where('bc_domain_id', $data->getDetail[0]->getDomain->id)
            ->first();

        $total = $data->getDetail->sum('id_total');

        $terbilang = (new CreateTempTable())->terbilang($total);

        $detail = (new WSAServices())->wsainvoice($data);
        if ($detail == false) {
            alert()->error('Error', 'Gagal mengambil data invoice')->persistent('Dismiss');
            return back();
        }
        $detail = collect($detail);
        $detail = $detail->groupBy('t_harga')
            ->map(function ($row) {
                $firstrow = $row->first();

                return [
                    "t_part" => $firstrow['t_part'],
                    "t_invnbr" => $firstrow['t_invnbr'],
                    "t_qtyinv" => $row->sum('t_qtyinv'),
                    "t_harga" => $firstrow['t_harga']
                ];
            })->values()->all();
            
        $pdf = PDF::loadview(
            'transaksi.laporan.pdf.pdf-invoice',
            [
                'data' => $data,
                'total' => $total,
                'terbilang' => $terbilang,
                'detail' => $detail,
                'bankacc' => $bankacc
            ]
        )->setPaper('A5', 'Landscape');

        return $pdf->stream();
    }

    public function printdetailinvoice($id)
    {
        $data = InvoiceMaster::with([
            'getDetail',
            'getSalesOrder.getCOMaster.getCustomer'
        ])->findOrFail($id);

        $detail = (new WSAServices())->wsadetailinvoice($data);
        if ($detail == false) {
            alert()->error('Error', 'Gagal mengambil data invoice')->persistent('Dismiss');
            return back();
        }
        $latestdate = $detail->whereNotNull('sj_eff_date')->sortByDesc('sj_eff_date')->first();
        $oldestdate = $detail->whereNotNull('sj_eff_date')->sortBy('sj_eff_date')->first();

        $iscontainer = $detail->whereIn('truck_tipe_id', [5, 6])->count() == 0 ? 0 : 1;

        $pdf = PDF::loadview(
            'transaksi.laporan.pdf.pdf-detail-invoice',
            [
                'data' => $data,
                'detail' => $detail,
                'latestdate' => $latestdate,
                'oldestdate' => $oldestdate,
                'iscontainer' => $iscontainer
            ]
        )->setPaper('A3', 'Landscape');

        return $pdf->stream();
    }

    // Invoice QAD
    public function printinvoiceqad($id)
    {
        $data = InvoiceDetail::with('getMaster.getSalesOrder.getCOMaster.getCustomer', 'getDomain')->findOrFail($id);
        $total = $data->id_total;

        $terbilang = (new CreateTempTable())->terbilang($total);

        $bankacc = BankCustomer::where('bc_customer_id', $data->getMaster->getSalesOrder->getCOMaster->getCustomer->id)
            ->where('bc_domain_id', $data->getDomain->id)
            ->first();

        $detail = (new WSAServices())->wsainvoiceqad($data);
        // dd($detail);
        if ($detail == false) {
            alert()->error('Error', 'Gagal mengambil data invoice')->persistent('Dismiss');
            return back();
        }

        $detail = collect($detail);
        $detail = $detail->groupBy('t_harga')
            ->map(function ($row) {
                $firstrow = $row->first();
                return [
                    "t_part" => $firstrow['t_part'],
                    "t_invnbr" => $firstrow['t_invnbr'],
                    "t_qtyinv" => $row->sum('t_qtyinv'),
                    "t_harga" => $firstrow['t_harga']
                ];
            })->values()->all();

        $pdf = PDF::loadview(
            'transaksi.laporan.pdf.pdf-invoice',
            [
                'data' => $data,
                'total' => $total,
                'terbilang' => $terbilang,
                'detail' => $detail,
                'bankacc' => $bankacc
            ]
        )->setPaper('A5', 'Landscape');

        return $pdf->stream();
    }

    public function printdetailinvoiceqad($id)
    {
        $data = InvoiceDetail::with('getMaster.getSalesOrder.getCOMaster.getCustomer')->findOrFail($id);

        $detail = (new WSAServices())->wsadetailinvoiceqad($data);
        if ($detail == false) {
            alert()->error('Error', 'Gagal mengambil data invoice')->persistent('Dismiss');
            return back();
        }

        $latestdate = $detail->whereNotNull('sj_eff_date')->sortByDesc('sj_eff_date')->first();
        $oldestdate = $detail->whereNotNull('sj_eff_date')->sortBy('sj_eff_date')->first();

        $iscontainer = $detail->whereIn('truck_tipe_id', [5, 6])->count() == 0 ? 0 : 1;

        $pdf = PDF::loadview(
            'transaksi.laporan.pdf.pdf-detail-invoice',
            [
                'data' => $data,
                'detail' => $detail,
                'latestdate' => $latestdate,
                'oldestdate' => $oldestdate,
                'iscontainer' => $iscontainer
            ]
        )->setPaper('A3', 'Landscape');

        return $pdf->stream();
    }


    public function loadinvoicefirst(Request $request)
    {
        $customer = Customer::get();
        $shipfrom = ShipFrom::get();
        $shipto = CustomerShipTo::get();
        $data = [];

        foreach ($customer as $key => $customers) {
            foreach ($shipfrom as $shipfroms) {
                foreach ($shipto as $shiptos) {
                    $data[] = [
                        'ip_cust_id' => $customers->id,
                        'ip_shipfrom_id' => $shipfroms->id,
                        'ip_customership_id' => $shiptos->id,
                    ];
                }
                InvoicePrice::insert($data);
                $data = [];
            }
            // dd($data);
        }
    }

    public function loadinvoice(Request $request)
    {
        if (($open = fopen(public_path() . "/InvoiceLoosing.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 2000, ";")) !== FALSE) {
                $history[] = $data;
            }
            // dd($history);
            $insertData = [];
            foreach ($history as $histories) {
                $customer = Customer::where('cust_code', $histories[0])->first()->id ?? '';
                $shipfrom = ShipFrom::where('sf_code', $histories[2])->first()->id ?? '';
                $custship = CustomerShipTo::where('cs_shipto', $histories[4])->first()->id ?? '';

                if ($shipfrom != '' && $custship != '' && $customer != '') {
                    $invoicelist = InvoicePrice::firstOrNew([
                        'ip_cust_id' => $customer,
                        'ip_shipfrom_id' => $shipfrom,
                        'ip_customership_id' => $custship,
                    ]);
                    $invoicelist->save();

                    $invoicehist = InvoicePriceHistory::firstOrNew([
                        'iph_tonase_price' => str_replace(',', '.', $histories[5]),
                        'iph_ip_id' => $invoicelist->id,
                    ]);

                    $invoicehist->save();

                    // $insertData[] = [
                    //     'iph_ip_id' => $invoicelist->id,
                    //     'iph_tonase_price' => str_replace(',','.',$histories[5])
                    // ];
                }
            }
            // InvoicePriceHistory::insert($insertData);

            fclose($open);
        }
    }

    public function loadinvoicecontainer(Request $request)
    {
        //if (($open = fopen(public_path() . "/InvoiceContainers.csv", "r")) !== FALSE) {
        if (($open = fopen(public_path() . "/invoicecontainer_05122022.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 2000, ",")) !== FALSE) {
                $history[] = $data;
            }
            // dd($history);
            $insertData = [];
            foreach ($history as $histories) {
                $customer = Customer::where('cust_code', $histories[0])->first()->id ?? '';
                $shipfrom = ShipFrom::where('sf_code', $histories[2])->first()->id ?? '';
                $custship = CustomerShipTo::where('cs_shipto', $histories[4])->first()->id ?? '';
                // dump($customer, $shipfrom, $custship);
                if ($shipfrom != '' && $custship != '' && $customer != '') {
                    // dump('1');
                    $invoicelist = InvoicePrice::firstOrNew([
                        'ip_cust_id' => $customer,
                        'ip_shipfrom_id' => $shipfrom,
                        'ip_customership_id' => $custship,
                    ]);
                    $invoicelist->save();

                    $tipetruck = $histories[6] == '20"' ? '5' : '6';

                    $existingprice = InvoicePriceHistory::query()
                        ->where('iph_ip_id', $invoicelist->id)
                        ->first();

                    if ($existingprice) {
                        if ($existingprice->iph_tipe_truck_id == null) {
                            $existingprice->iph_tipe_truck_id = $tipetruck;
                            $existingprice->iph_trip_price = str_replace(',', '.', $histories[5]);
                            $existingprice->save();
                        } else {
                            $newprice = new InvoicePriceHistory();
                            $newprice->iph_ip_id = $invoicelist->id;
                            $newprice->iph_tipe_truck_id = $tipetruck;
                            $newprice->iph_trip_price = str_replace(',', '.', $histories[5]);
                            $newprice->iph_tonase_price = $existingprice->iph_tonase_price;
                            $newprice->save();
                        }
                    } else {
                        $newprice = new InvoicePriceHistory();
                        $newprice->iph_ip_id = $invoicelist->id;
                        $newprice->iph_tipe_truck_id = $tipetruck;
                        $newprice->iph_trip_price = str_replace(',', '.', $histories[5]);
                        $newprice->save();
                    }
                }
            }
            // dd($insertData);
            // InvoicePriceHistory::insert($insertData);

            fclose($open);
        }
    }
}
