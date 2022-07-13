<?php

namespace App\Exports;

use App\Models\Master\Truck;
use App\Models\Transaksi\ReportBiaya;
use App\Models\Transaksi\SuratJalan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportTotalanSupirLoosingHSST implements FromView, WithColumnWidths, ShouldAutoSize, WithStyles 
{

    public function __construct($datefrom,$dateto)
    {
        $this->datefrom      = $datefrom;
        $this->dateto        = $dateto;
    }
    
    public function view() : view
    {
        $datefrom    = $this->datefrom;
        $dateto      = $this->dateto;

        $data = SuratJalan::query();
        $rbhist = ReportBiaya::query();

        if($datefrom){
            $data->where('sj_eff_date','>=',$datefrom);
            $rbhist->where('rb_eff_date','>=',$datefrom);
        }

        if($dateto){
            $data->where('sj_eff_date','<=',$dateto);
            $rbhist->where('rb_eff_date','>=',$dateto);
        }

        $data = $data->with(['getTruck.getUserDriver','getTruck.getTipe'])
                    //  ->whereRelation('getTruck.getTipe', 'tt_code', '2EXL') // Hardcode Tipe Truck HSST
                    //  ->orWhereRelation('getTruck.getTipe', 'tt_code', '3EXL')
                     ->groupBy('sj_truck_id')
                     ->selectRaw('sj_truck_id,sum(sj_default_sangu) as defaultSangu, sum(sj_tot_sangu) as totalSangu')
                     ->get();
        
        $rbhist =  $rbhist->where('rb_is_active',1)
                          ->with(['getTruck.getUserDriver','getTruck.getTipe'])
                        //   ->whereRelation('getTruck.getTipe', 'tt_code', '2EXL') // Hardcode Tipe Truck HSST
                        //   ->orWhereRelation('getTruck.getTipe', 'tt_code', '3EXL')
                          ->groupBy('rb_truck_id')
                          ->selectRaw('rb_truck_id,sum(CASE WHEN rb_is_pemasukan = 1 then rb_nominal else - rb_nominal end) as total')
                          ->get();

        $listtruck = Truck::with(['getTipe','getUserDriver'])
                            // ->whereRelation('getTipe', 'tt_code', '2EXL') // Hardcode Tipe Truck HSST
                            // ->orWhereRelation('getTipe', 'tt_code', '3EXL')
                            ->get();
                            
        return view('transaksi.laporan.excel.report-total-sopir-loosing-hsst',
                    compact('data','listtruck','datefrom','dateto','rbhist'));
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
            'A' => 20,
            'B' => 30,    
            'C' => 10,    
            'D' => 10,    
            'E' => 10,
            'F' => 10,
            'G' => 20,
            'H' => 20,
            'I' => 5,
            'J' => 15,
            'K' => 20,
            'L' => 20,
        ];
    }
}
