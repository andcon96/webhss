<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Transaksi\ReportBiaya;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportByBiayaTambahan implements FromView, WithColumnWidths, ShouldAutoSize
{
    
    public function __construct($datefrom,$dateto,$tipe)
    {
        $this->datefrom      = $datefrom;
        $this->dateto        = $dateto;
        $this->tipe          = $tipe;
    }

    public function view() : view
    {
        $datefrom    = $this->datefrom;
        $dateto      = $this->dateto;
        $tipe        = $this->tipe; // 1 Loosing 2 Container, Tipe Truck 1-4 Loosing & 5-6 Container

        $rbhist = ReportBiaya::query()
                ->with(['getDetail','getTruck']);
        
        if($tipe == 2){
            $rbhist->whereRelation('getTruck',function($query){
                $query->whereIn('truck_tipe_id',['5','6']);
            });
        }else{
            $rbhist->whereRelation('getTruck',function($query){
                $query->whereIn('truck_tipe_id',['1','2','3','4']);
            });
        }
        
        $rbhist = $rbhist->orderBy('rb_truck_id')
                ->orderBy('rb_eff_date')
                ->get();
                
        return view('transaksi.laporan.excel.report-biaya-tambahan', [
            'rbhist' => $rbhist,
            'datefrom' => $datefrom,
            'dateto' => $dateto,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'B' => 25,
            'C' => 25,
            'D' => 20,
            'E' => 10, 
            'F' => 20, 
            'G' => 20,            
        ];
    }
}
