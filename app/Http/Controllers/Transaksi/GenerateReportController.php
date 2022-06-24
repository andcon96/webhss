<?php

namespace App\Http\Controllers\Transaksi;

use App\Exports\ReportByMonthExport;
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

        return Excel::download(new ReportByMonthExport($datefrom,$dateto,$truck), 'ReportByMonth.xlsx');
    }
}
