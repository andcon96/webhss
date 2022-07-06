<?php

namespace App\Http\Controllers\Transaksi;

use App\Exports\ReportByMonthExport;
use App\Exports\ReportLoosingHSST;
use App\Exports\ReportTotalanSupirLoosingHSST;
use App\Http\Controllers\Controller;
use App\Models\Master\Truck;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GenerateReportController extends Controller
{
    public function index()
    {
        $truck = Truck::get();

        return view('transaksi.laporan.index',compact('truck'));
    }

    public function reportsangu(Request $request)
    {
        $datefrom = $request->datefrom;
        $dateto = $request->dateto;
        $truck = $request->truck;
        $report = $request->report;

        if($report == '1'){
            // By Date
            // dd('notyet');
            // return Excel::download(new ReportByMonthExport($datefrom,$dateto,$truck), 'laporan-sangu.xlsx');
        }elseif($report == '2'){
            // By Truck by Date
            return Excel::download(new ReportByMonthExport($datefrom,$dateto,$truck), 'ReportByMonth.xlsx');
        }elseif($report == '3'){
            // Loosing HSST
            return Excel::download(new ReportLoosingHSST($datefrom,$dateto), 'ReportLoosingHSST.xlsx');
        }elseif($report == '4'){
            // Totalan Supir Loosing HSST
            return Excel::download(new ReportTotalanSupirLoosingHSST($datefrom,$dateto), 'ReportTotalSupirLoosingHSST.xlsx');
        }
    }
}
