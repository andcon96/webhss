<?php

namespace App\Exports;

use App\Models\Master\TipeTruck;
use App\Models\Master\Truck;
use App\Models\Transaksi\SuratJalan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportByTipeTruck implements FromView, WithColumnWidths, ShouldAutoSize
{
    public function __construct($datefrom,$dateto,$tipetruck)
    {
        $this->datefrom      = $datefrom;
        $this->dateto        = $dateto;
        $this->tipetruck         = $tipetruck;
    }

    public function view() : view
    {
        $datefrom    = $this->datefrom;
        $dateto      = $this->dateto;
        $tipetruck   = $this->tipetruck;

        $truckid = Truck::where('truck_tipe_id',$tipetruck)->pluck('id')->toArray();

        $tipetruck = TipeTruck::find($tipetruck);

        $suratjalan = SuratJalan::query()
                        ->with([
                            'getTruck',
                            'getSOMaster.getShipFrom',
                            'getSOMaster.getShipTo',
                            'getSOMaster.getCOMaster.getCustomer'])
                        ->whereIn('sj_truck_id',$truckid)
                        ->whereBetween('sj_eff_date',[$datefrom,$dateto])
                            ->orderBy('sj_truck_id','asc')
                            ->orderBy('sj_nbr','asc')
                        ->get();

        // dd($datefrom,$dateto,$tipetruck,$truckid,$suratjalan,$tipetruck);

        return view('transaksi.laporan.excel.report-by-tipetruck',
                        compact('suratjalan','datefrom','dateto','tipetruck'));
    }

    public function columnWidths(): array
    {
        return [
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 40,
            'G' => 40,
            'H' => 40,            
        ];
    }
}
