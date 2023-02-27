<?php

namespace App\Exports;

use App\Models\Transaksi\SuratJalan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportSPK implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings
{
    public function __construct($sonbr,$sjnbr,$customer,$truck,$datefrom,$dateto)
    {
        $this->sonbr        = $sonbr;
        $this->sjnbr        = $sjnbr;
        $this->customer     = $customer;
        $this->truck        = $truck;
        $this->datefrom     = $datefrom;
        $this->dateto       = $dateto;
    }
    
    public function collection()
    {
        $sonbr        = $this->sonbr;
        $sjnbr        = $this->sjnbr;
        $customer     = $this->customer;
        $truck        = $this->truck;
        $datefrom     = $this->datefrom;
        $dateto       = $this->dateto;

        $spk = SuratJalan::query()
                    ->with(['getSOMaster.getCOMaster.getCustomer','getTruck']);

        if($sonbr){
            $spk->where('sj_so_mstr_id',$sonbr);
        }
        if($sjnbr){
            $spk->where('sj_nbr',$sjnbr);
        }
        if($customer){
            $spk->whereRelation('getSOMaster.getCOMaster','co_cust_code',$customer);
        }
        if($truck){
            $spk->where('sj_truck_id',$truck);
        }
        if($datefrom){
            $spk->where('sj_eff_date','>=',$datefrom);
        }
        if($dateto){
            $spk->where('sj_eff_date','<=',$dateto);
        }


        $spk = $spk->get();

        return $spk;
    }
    
    public function map($spk): array
    {
        return [
            $spk->sj_nbr,
            $spk->getSOMaster->so_nbr,
            $spk->sj_eff_date,
            $spk->sj_remark,
            $spk->sj_status,
            $spk->getTruck->truck_no_polis,
            $spk->sj_jmlh_trip,
        ];
    }
    public function headings(): array
    {
        return [
            'SPK Number',
            'SO Number',
            'SPK Eff Date',
            'SPK Remarks',
            'SPK Status',
            'Nopol',
            'Jumlah Trip',
        ];
    }
}
