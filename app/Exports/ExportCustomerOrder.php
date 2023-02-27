<?php

namespace App\Exports;

use App\Models\Transaksi\CustomerOrderMstr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportCustomerOrder implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    
    public function __construct($conbr,$cust)
    {
        $this->conbr      = $conbr;
        $this->cust       = $cust;
    }

    public function collection()
    {
        $conbr    = $this->conbr;
        $cust     = $this->cust;

        $co = CustomerOrderMstr::query()
                    ->with(['getBarang', 'getCustomer']);

        if($conbr){
            $co->where('co_nbr',$conbr);
        }
        if($cust){
            $co->where('co_cust_code',$cust);
        }

        $co = $co->get();

        return $co;
    }

    public function map($co): array
    {
        return [
            $co->co_nbr,
            $co->getCustomer->cust_desc,
            $co->co_type,
            $co->co_status,
            $co->co_remark,
            $co->co_kapal,
            $co->getBarang->barang_deskripsi ?? '',
        ];
    }
    public function headings(): array
    {
        return [
            'CO Number',
            'Customer',
            'CO Type',
            'CO Status',
            'CO Remark',
            'Kapal',
            'Barang',
        ];
    }
}
