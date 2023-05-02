<?php

namespace App\Exports;

use App\Models\Transaksi\SalesOrderMstr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportSalesOrder implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    
    public function __construct($sonbr,$conbr,$shipfrom,$shipto,$customer,$status)
    {
        $this->conbr        = $conbr;
        $this->sonbr        = $sonbr;
        $this->customer     = $customer;
        $this->shipfrom     = $shipfrom;
        $this->shipto       = $shipto;
        $this->status       = $status;
    }

    public function collection()
    {
        $conbr        = $this->conbr;
        $customer     = $this->customer;
        $shipfrom     = $this->shipfrom;
        $sonbr        = $this->sonbr;
        $shipto       = $this->shipto;
        $status       = $this->status;

        $so = SalesOrderMstr::query()
                        ->with(['getCOMaster.getCustomer','getShipTo','getShipFrom']);

        if($conbr){
            $so->where('so_co_mstr_id',$conbr);
        }
        if($shipfrom){
            $so->where('so_ship_from',$shipfrom);
        }
        if($sonbr){
            $so->where('id',$sonbr);
        }
        if($shipto){
            $so->where('so_ship_to',$shipto);
        }
        if($status){
            $so->where('so_status',$status);
        }
        if($customer){
            $so->whereRelation('getCOMaster','co_cust_code',$customer);
        }

        $so  = $so->get();
        
        return $so;
    }

    
    public function map($so): array
    {
        return [
            $so->so_nbr,
            $so->getCOMaster->co_nbr,
            $so->getCOMaster->getCustomer->cust_desc,
            $so->so_ship_from,
            $so->so_ship_to,
            $so->so_due_date,
            $so->so_status,
            $so->so_remark,
        ];
    }
    public function headings(): array
    {
        return [
            'SO Number',
            'CO Number',
            'Customer',
            'Ship From',
            'Ship To',
            'SO Due Date',
            'SO Status',
            'SO Remark',
        ];
    }
}
