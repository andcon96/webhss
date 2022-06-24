<?php

namespace App\Exports;

use App\Models\Master\Truck;
use App\Models\Transaksi\ReportBiaya;
use App\Models\Transaksi\SuratJalan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportByMonthExport implements FromView, ShouldAutoSize
{
    public function __construct($datefrom,$dateto,$truck)
    {
        $this->datefrom      = $datefrom;
        $this->dateto        = $dateto;
        $this->truck         = $truck;
    }

    public function view() : view
    {
        $datefrom    = $this->datefrom;
        $dateto      = $this->dateto;
        $truck       = $this->truck;

        $truckcol = Truck::findOrFail($truck);

        $nopol = $truckcol->truck_no_polis;

        $data = SuratJalan::query();

        if($datefrom){
            $data->where('sj_eff_date','>=',$datefrom);
        }
        if($dateto){
            $data->where('sj_eff_date','<=',$dateto);
        }
        if($truck){
            $data->where('sj_truck_id',$truck);
        }

        $data = $data->with('getDetail.getItem',
                            'getSOMaster.getCOMaster.getCustomer',
                            'getSOMaster.getShipFrom',
                            'getSOMaster.getShipTo'
                            )->get();
                            
        $totalrb = ReportBiaya::where('rb_truck_id',$truck)->sum('rb_nominal');

        return view('transaksi.laporan.excel.report-date-range',
                        compact('data','datefrom','dateto','nopol','totalrb'));
    }
}
