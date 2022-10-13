<?php

namespace App\Http\Controllers\Transaksi;

use App\Exports\ReportByBiayaTambahan;
use App\Exports\ReportByMonthExport;
use App\Exports\ReportByTipeTruck;
use App\Exports\ReportLoosingHSST;
use App\Exports\ReportTotalanSupirLoosingHSST;
use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\DriverNopol;
use App\Models\Master\TipeTruck;
use App\Models\Master\Truck;
use App\Models\Transaksi\CicilanHistory;
use App\Services\CreateTempTable;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class GenerateReportController extends Controller
{
    public function index()
    {
        $domainUser = Auth::user()->domain;
        $truck = Truck::query()->with('getDomain');
        $domain = Domain::query();
        $tipetruck = TipeTruck::get();

        if($domainUser){
            $truck->whereRelation('getDomain','id',$domainUser);
            $domain->where('id',$domainUser);
        }

        $truck = $truck->get();
        $domain = $domain->get();

        return view('transaksi.laporan.index', compact('truck', 'domain', 'tipetruck'));
    }

    public function reportsangu(Request $request)
    {
        $datefrom = $request->datefrom;
        $dateto = $request->dateto;
        $truck = $request->truck;
        $report = $request->report;
        $tipetruck = $request->tipetruck;
        $domain = $request->domain;


        switch ($request->aksi) {
            case 1:
                if ($report == '2') {
                    // By Truck by Date
                    return Excel::download(new ReportByMonthExport($datefrom, $dateto, $truck), 'ReportByMonth.xlsx');
                } elseif ($report == '3') {
                    // Loosing HSST
                    return Excel::download(new ReportLoosingHSST($datefrom, $dateto), 'ReportLoosingHSST.xlsx');
                } elseif ($report == '4') {
                    // Totalan Supir Loosing HSST
                    return Excel::download(new ReportTotalanSupirLoosingHSST($datefrom, $dateto), 'ReportTotalSupirLoosingHSST.xlsx');
                } elseif ($report == '5'){
                    // Report by Tipe Truck
                    return Excel::download(new ReportByTipeTruck($datefrom,$dateto,$tipetruck), 'ReportByTipeTruck.xlsx');
                } elseif ($report == '6'){
                    // Report by Biaya Tambahan

                    return Excel::download(new ReportByBiayaTambahan($datefrom,$dateto), 'ReportBiayaTambahan.xlsx');
                }
                break;
            case 2:

                if ($report == '2') {
                    return redirect()->route('updatePreview')->with(['data' => $request->all()]);
                } elseif ($report == '3') {
                    $datefrom = $request->datefrom;
                    $dateto = $request->dateto;
                    $domain = $request->domain;

                    $getData = (new CreateTempTable())->getDataReportLoosingHSST($domain, $datefrom, $dateto);
                    $data = $getData['data'];
                    $listtruck = $getData['listtruck'];
                    $period = $getData['period'];
                    $periodcount = $getData['periodcount'];

                    $pdf = PDF::loadview(
                        'transaksi.laporan.pdf.pdf-loosing-hsst',
                        [
                            'data' => $data,
                            'listtruck' => $listtruck,
                            'period' => $period,
                            'datefrom' => $datefrom,
                            'dateto' => $dateto,
                            'periodcount' => $periodcount
                        ]
                    )->setPaper('A3', 'Landscape');

                    return $pdf->stream();
                } elseif ($report == '4') {
                    $datefrom = $request->datefrom;
                    $dateto = $request->dateto;
                    $tipe = $request->tipe;
                    $domain = $request->domain;

                    $getData = (new CreateTempTable())->getDataTotalanSupirLoosing($domain, $datefrom, $dateto, $tipe);
                    $data = $getData['data'];
                    $listtruck = $getData['listtruck'];
                    $rbhist = $getData['rbhist'];
                    
                    $pdf = PDF::loadview(   
                        'transaksi.laporan.pdf.pdf-totalan-loosing',
                        [
                            'data' => $data,
                            'listtruck' => $listtruck,
                            'rbhist' => $rbhist,
                            'datefrom' => $datefrom,
                            'dateto' => $dateto,
                            'domain' => $domain,
                            'tipe' => $tipe
                        ]
                    )->setPaper('A3', 'Landscape');

                    return $pdf->stream();
                }
                break;
        }
    }

    public function updatepreview(Request $request)
    {
        $data = Session::get('data');
        // dd($data);
        if (!$data) {
            alert()->error('Error', 'Terjadi Kesalahan, silahkan dicoba lagi')->persistent('Dismiss');
            return back();
        }

        $nopolid = $data['truck'];
        $truck = Truck::findOrFail($nopolid);
        $nopol = $truck->truck_no_polis;

        $report = $data['report'];
        $datefrom = $data['datefrom'];
        $dateto = $data['dateto'];

        $driver = DriverNopol::with('getDriver')->where('dn_truck_id',$nopolid)->get();
        // dd($driver, $data);
        return view('transaksi.laporan.info-per-nopol', compact('nopol', 'report', 'datefrom', 'dateto', 'nopolid', 'driver'));
    }

    public function getcicilan(Request $request)
    {
        if($request->ajax())
        {
            $cicilan = CicilanHistory::query()
                        ->with('getCicilan.getDriverNopol')
                        ->whereRelation('getCicilan.getDriverNopol','dn_driver_id',$request->driverid)
                        ->whereRelation('getCicilan.getDriverNopol','dn_truck_id',$request->truckid)
                        ->where('hc_eff_date','>=',$request->datefrom)
                        ->where('hc_eff_date','<=',$request->dateto)
                        ->get();

            // $cicilan = Cicilan::query()
            //             ->with('getDriverNopol')
            //             ->whereRelation('getDriverNopol','dn_driver_id',$request->driverid)
            //             ->whereRelation('getDriverNopol','dn_truck_id',$request->truckid)
            //             ->where('cicilan_eff_date','>=',$request->datefrom)
            //             ->where('cicilan_eff_date','<=',$request->dateto)
            //             ->get();

            return $cicilan;
        }
    }

    public function printpdf(Request $request)
    {
        $truck = $request->truck;
        $driver = $request->driver;
        $datefrom = $request->datefrom;
        $dateto = $request->dateto;
        
        $getData = (new CreateTempTable())->getDataReportPerNopol($truck, $datefrom, $dateto, $driver);

        $data = $getData['data'];
        $rbhist = $getData['rbhist'];
        $totalrb = $getData['totalrb'];
        $nopol = $getData['nopol'];
        $histcicilan = $getData['histcicilan'];
        $driver = $getData['driver'];
        // dd($histcicilan,$getData);
        $pdf = PDF::loadview(
            'transaksi.laporan.pdf.pdf-per-nopol',
            [
                'data' => $data,
                'rbhist' => $rbhist,
                'totalrb' => $totalrb,
                'nopol' => $nopol,
                'histcicilan' => $histcicilan,
                'driver' => $driver,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
            ]
        )->setPaper([0, 0, 684, 792], 'Potrait');
        // ->setPaper('A4', 'Potrait');

        return $pdf->stream();
    }
}
