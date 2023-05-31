<?php

namespace App\Exports;

use App\Models\Transaksi\SuratJalan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportTonaseRill implements FromView, ShouldAutoSize
{
    public function __construct($listso, $listspk, $listcustomer, $listtruck, $listshipfrom, $listshipto)
    {
        $this->listso         = $listso;
        $this->listspk        = $listspk;
        $this->listcustomer   = $listcustomer;
        $this->listtruck      = $listtruck;
        $this->listshipfrom   = $listshipfrom;
        $this->listshipto     = $listshipto;
    }

    public function view(): view
    {
        $idso    = $this->listso;
        $idspk      = $this->listspk;
        $idcust       = $this->listcustomer;
        $idtruck       = $this->listtruck;
        $idshipfrom       = $this->listshipfrom;
        $idshipto       = $this->listshipto;


        $suratjalan = SuratJalan::query()
            ->with('getDetail', 'getSOMaster.getCOMaster.getCustomer', 'getTruck', 'getRuteHistory', 'getSOMaster.getShipFrom', 'getSOMaster.getShipTo');

        if($idso){
            $suratjalan->where('sj_so_mstr_id',$idso);
        }

        if($idspk){
            $suratjalan->where('id',$idspk);
        }

        if($idcust){
            $suratjalan->whereRelation('getSOMaster.getCOMaster.getCustomer','id',$idcust);
        }

        if($idtruck){
            $suratjalan->whereRelation('getTruck','id',$idtruck);
        }

        if($idshipfrom){
            $suratjalan->whereRelation('getSOMaster.getShipFrom','id',$idshipfrom);
        }

        if($idshipto){
            $suratjalan->whereRElation('getSOMaster.getShipTo','id',$idshipto);
        }

        $suratjalan = $suratjalan->get();

        // dd($suratjalan);

        return view(
            'transaksi.laporan.excel.report-tonaserill',
            compact('suratjalan')
        );
    }
}
