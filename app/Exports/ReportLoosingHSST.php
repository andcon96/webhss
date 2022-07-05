<?php

namespace App\Exports;

use App\Models\Master\Truck;
use App\Models\Transaksi\SuratJalan;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportLoosingHSST implements FromView, WithColumnWidths, ShouldAutoSize, WithStyles 
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

        if($datefrom){
            $data->where('sj_eff_date','>=',$datefrom);
        }

        if($dateto){
            $data->where('sj_eff_date','<=',$dateto);
        }

        $data = $data->where('sj_status','=','Closed')
                     ->with(['getTruck.getUserDriver','getTruck.getTipe'])
                     ->whereRelation('getTruck.getTipe', 'tt_code', '2EXL') // Hardcode Tipe Truck HSST
                     ->orWhereRelation('getTruck.getTipe', 'tt_code', '3EXL')
                     ->groupBy('sj_truck_id','sj_eff_date')
                     ->selectRaw('sj_truck_id,sj_eff_date,sum(sj_default_sangu) as sangu')
                     ->get();

        $listtruck = Truck::with(['getTipe','getUserDriver'])
                            ->whereRelation('getTipe', 'tt_code', '2EXL') // Hardcode Tipe Truck HSST
                            ->orWhereRelation('getTipe', 'tt_code', '3EXL')
                            ->get();
        
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod(new DateTime($datefrom), $interval, new DateTime($dateto));

        // dd($data);

        return view('transaksi.laporan.excel.report-loosing-hsst',
                    compact('data','listtruck','period','datefrom','dateto'));
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
        ];
    }
}
