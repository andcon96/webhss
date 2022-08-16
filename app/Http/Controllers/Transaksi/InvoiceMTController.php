<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Domain;
use App\Models\Master\InvoicePrice;
use App\Models\Master\InvoicePriceHistory;
use App\Models\Master\ShipFrom;
use App\Models\Transaksi\InvoiceMaster;
use App\Models\Transaksi\InvoiceDetail;
use App\Models\Transaksi\SalesOrderMstr;
use App\Services\WSAServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_invoice = InvoiceMaster::all();
        $list_sonbr = SalesOrderMstr::all();
        $data = InvoiceMaster::with('getDetail','getSalesOrder')->paginate(10);

        return view('transaksi.invoice.index', compact('data','list_invoice','list_sonbr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_sonbr = SalesOrderMstr::all();
        $list_domain = Domain::all();
        return view('transaksi.invoice.create', compact('list_sonbr','list_domain'));
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
            $invmstr = new InvoiceMaster();
            $invmstr->im_nbr = 'INV2022/000000001';
            $invmstr->im_so_mstr_id = $request->sonbr;
            $invmstr->im_date = $request->im_date;
            $invmstr->save();

            $invmstr_id = $invmstr->id;
            foreach($request->domain as $key => $datas){
                $invdet = new InvoiceDetail();
                $invdet->id_im_mstr_id = $invmstr_id;
                $invdet->id_domain = $datas;
                $invdet->id_nbr = $request->ivnbrv[$key];
                $invdet->id_total = str_replace(',','',$request->price[$key]);
                $invdet->save();
            }

            DB::commit();
            alert()->success('Success', 'Save Berhasil')->persistent('Dismiss');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Data gagal disimpan')->persistent('Dismiss');
            return back();
        }
    }

    public function checkInvoice(Request $request)
    {
        if($request->ajax()){
            $checkData = (new WSAServices())->wsacheckinvoice($request->domain, $request->invoiceqad);
            if($checkData === false){
                return response()->json(['error' => 'WSA Failed'],404);
            }

            return number_format((Float)$checkData,0);
        }
    }

    
    public function loadinvoicefirst(Request $request)
    {
        $customer = Customer::get();
        $shipfrom = ShipFrom::get();
        $shipto = CustomerShipTo::get();
        $data = [];

        foreach($customer as $key => $customers){
            foreach($shipfrom as $shipfroms){
                foreach($shipto as $shiptos){
                    $data[] = [
                        'ip_cust_id' => $customers->id,
                        'ip_shipfrom_id' => $shipfroms->id,
                        'ip_customership_id' => $shiptos->id,
                    ];
                }
            }
            // dd($data);
            InvoicePrice::insert($data);
            $data = [];
        }
    }

    public function loadinvoice(Request $request)
    {
        if (($open = fopen(public_path() . "/historyinvoice.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 2000, ",")) !== FALSE) {
                $history[] = $data;
            }
            // dd($history);
            $insertData = [];
            foreach($history as $histories){
                $customer = Customer::where('cust_code', $histories[0])->first()->id ?? '';
                $shipfrom = ShipFrom::where('sf_code', $histories[2])->first()->id ?? '';
                $custship = CustomerShipTo::where('cs_shipto', $histories[4])->first()->id ?? '';

                if($shipfrom != '' && $custship != '' && $customer != ''){
                    $invoicelist = InvoicePrice::firstOrNew([
                                        'ip_cust_id' => $customer,
                                        'ip_shipfrom_id' => $shipfrom,
                                        'ip_customership_id' => $custship,
                                    ]);
                    $invoicelist->save();
                    
                    $insertData[] = [
                        'iph_ip_id' => $invoicelist->id,
                        'iph_tonase_price' => str_replace(',','.',$histories[5])
                    ];
                }
                
            }
            InvoicePriceHistory::insert($insertData);

            fclose($open);
        }    
    }
}
