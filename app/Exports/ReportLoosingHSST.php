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
    public function __construct($datefrom, $dateto, $domain, $subdomain)
    {
        $this->datefrom      = $datefrom;
        $this->dateto        = $dateto;
        $this->domain        = $domain;
        $this->subdomain        = $subdomain;
    }

    public function view(): view
    {
        $datefrom    = $this->datefrom;
        $dateto      = $this->dateto;
        $domain      = $this->domain;
        $subdomain   = $this->subdomain;

        $data = SuratJalan::query()
            ->with(['getTruck.getUserDriver', 'getTruck.getTipe']);
        $listtruck = Truck::query()->with(['getTipe', 'getUserDriver']);

        if ($datefrom) {
            $data->where('sj_eff_date', '>=', $datefrom);
        }

        if ($dateto) {
            $data->where('sj_eff_date', '<=', $dateto);
        }

        if ($domain) {
            $data->whereRelation('getTruck', 'truck_domain', $domain);
            $listtruck->where('truck_domain',$domain);
        }

        if ($subdomain) {
            $data->whereRelation('getTruck', 'truck_sub_domain', $subdomain);
            $listtruck->where('truck_sub_domain',$subdomain);
        }

        $data = $data
            ->where('sj_status', 'Closed')
            ->groupBy('sj_truck_id', 'sj_eff_date')
            ->selectRaw('sj_truck_id,sj_eff_date,sum(sj_default_sangu) as sangu')
            ->get();

        $listtruck = $listtruck->get();

        $interval = DateInterval::createFromDateString('1 day');
        $end = new DateTime($dateto);
        $end->modify('+1 day');
        $period = new DatePeriod(new DateTime($datefrom), $interval, $end);

        return view(
            'transaksi.laporan.excel.report-loosing-hsst',
            compact('data', 'listtruck', 'period', 'datefrom', 'dateto')
        );
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
