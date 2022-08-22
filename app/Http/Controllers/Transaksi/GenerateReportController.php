<?php

namespace App\Http\Controllers\Transaksi;

use App\Exports\ReportByMonthExport;
use App\Exports\ReportByTipeTruck;
use App\Exports\ReportLoosingHSST;
use App\Exports\ReportTotalanSupirLoosingHSST;
use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\TipeTruck;
use App\Models\Master\Truck;
use App\Models\Transaksi\SuratJalan;
use App\Services\CreateTempTable;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class GenerateReportController extends Controller
{
    public function index()
    {
        $truck = Truck::get();

        $domain = Domain::get();

        $tipetruck = TipeTruck::get();

        return view('transaksi.laporan.index', compact('truck', 'domain', 'tipetruck'));
    }

    public function reportsangu(Request $request)
    {
        $datefrom = $request->datefrom;
        $dateto = $request->dateto;
        $truck = $request->truck;
        $report = $request->report;
        $tipetruck = $request->tipetruck;

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
                    $domain = $request->domain;

                    $getData = (new CreateTempTable())->getDataTotalanSupirLoosing($domain, $datefrom, $dateto);
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
                            'dateto' => $dateto
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

        return view('transaksi.laporan.info-per-nopol', compact('nopol', 'report', 'datefrom', 'dateto', 'nopolid'));
    }

    public function printpdf(Request $request)
    {
        $truck = $request->truck;
        $datefrom = $request->datefrom;
        $dateto = $request->dateto;
        $tabungan = $request->tabungan;
        $cicilan = $request->cicilan;

        $getData = (new CreateTempTable())->getDataReportPerNopol($truck, $datefrom, $dateto);

        $data = $getData['data'];
        $rbhist = $getData['rbhist'];
        $totalrb = $getData['totalrb'];
        $nopol = $getData['nopol'];

        $pdf = PDF::loadview(
            'transaksi.laporan.pdf.pdf-per-nopol',
            [
                'data' => $data,
                'rbhist' => $rbhist,
                'totalrb' => $totalrb,
                'nopol' => $nopol,
                'tabungan' => str_replace(',', '', $tabungan),
                'cicilan' => str_replace(',', '', $cicilan),
                'datefrom' => $datefrom,
                'dateto' => $dateto,
            ]
        )->setPaper([0, 0, 684, 792], 'Potrait');
        // ->setPaper('A4', 'Potrait');

        return $pdf->stream();
    }
}
