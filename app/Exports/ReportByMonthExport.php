<?php

namespace App\Exports;

use App\Models\Master\Truck;
use App\Models\Transaksi\ReportBiaya;
use App\Models\Transaksi\SuratJalan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportByMonthExport implements FromView, WithColumnWidths, ShouldAutoSize, WithStyles
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
        $openSPK = SuratJalan::query();
        $rbhist = ReportBiaya::query();

        if($datefrom){
            $data->where('sj_eff_date','>=',$datefrom);
            $openSPK->where('sj_eff_date','>=',$datefrom);
            $rbhist->where('rb_eff_date','>=',$datefrom);
        }
        if($dateto){
            $data->where('sj_eff_date','<=',$dateto);
            $openSPK->where('sj_eff_date','<=',$dateto);
            $rbhist->where('rb_eff_date','<=',$dateto);
        }
        if($truck){
            $data->where('sj_truck_id',$truck);
            $openSPK->where('sj_truck_id',$truck);
            $rbhist->where('rb_truck_id',$truck);
        }

        $rbhist = $rbhist->with('getTruck')->get();

        $openSPK = $openSPK->with('getDetail.getItem',
                                'getSOMaster.getCOMaster.getCustomer',
                                'getSOMaster.getShipFrom',
                                'getSOMaster.getShipTo',
                                'getRuteHistory.getRute',
                                )
                            ->where('sj_status','!=','Closed')    
                            ->get();

        $data = $data->with('getDetail.getItem',
                            'getSOMaster.getCOMaster.getCustomer',
                            'getSOMaster.getShipFrom',
                            'getSOMaster.getShipTo',
                            'getRuteHistory.getRute',
                            )
                        ->where('sj_status','Closed')    
                        ->get();
        
                            
        $totalrb = ReportBiaya::where('rb_truck_id',$truck)->sum('rb_nominal');

        return view('transaksi.laporan.excel.report-date-range',
                        compact('data','datefrom','dateto','nopol','totalrb','rbhist','openSPK'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 20, 'align' => 'center']],
            2    => ['font' => ['size' => 12]],
            3    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 30,
            'C' => 15,
            'D' => 30,
            'E' => 30,
            'F' => 10,
            'H' => 20            
        ];
    }
}
