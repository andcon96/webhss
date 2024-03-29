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
        $subdomain      = $this->subdomain;

        $data = SuratJalan::query()->with(['getTruck.getUserDriver', 'getTruck.getTipe']);
        $rbhist = ReportBiaya::query();
        $listtruck = Truck::query()->with(['getTipe', 'getUserDriver']);

        if ($datefrom) {
            $data->where('sj_eff_date', '>=', $datefrom);
            $rbhist->where('rb_eff_date', '>=', $datefrom);
        }

        if ($dateto) {
            $data->where('sj_eff_date', '<=', $dateto);
            $rbhist->where('rb_eff_date', '>=', $dateto);
        }

        if ($domain) {
            $data->whereRelation('getTruck', 'truck_domain', $domain);
            $listtruck->where('truck_domain',$domain);
        }

        if ($subdomain){
            $data->whereRelation('getTruck', 'truck_sub_domain', $subdomain);
            $listtruck->where('truck_sub_domain',$subdomain);
        }


        $data = $data
            ->where('sj_status', 'Closed')
            ->groupBy('sj_truck_id')
            ->selectRaw('sj_truck_id,sum(sj_default_sangu) as defaultSangu, sum(sj_tot_sangu) as totalSangu')
            ->get();

        $rbhist =  $rbhist->where('rb_is_active', 1)
            ->with(['getTruck.getUserDriver', 'getTruck.getTipe'])
            ->groupBy('rb_truck_id')
            ->selectRaw('rb_truck_id,sum(CASE WHEN rb_is_pemasukan = 1 then - rb_nominal else rb_nominal end) as total')
            ->get();

        $listtruck = $listtruck->get();


        return view(
            'transaksi.laporan.excel.report-total-sopir-loosing-hsst',
            compact('data', 'listtruck', 'datefrom', 'dateto', 'rbhist')
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
