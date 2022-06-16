<?php

namespace App\Models\Transaksi;

use App\Models\Master\TruckDriver;
use App\Models\Master\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SOHistTrip extends Model
{
    use HasFactory;

    public $table = 'so_hist_trip';
    
    public $timestamps = false;
    
    public function getMaster()
    {
        return $this->belongsTo(SalesOrderMstr::class, 'soh_so_mstr_id');
    }

    public function getSangu()
    {
        return $this->belongsTo(SalesOrderSangu::class, 'soh_so_mstr_id', 'sos_so_mstr_id');
    }

    public function getTruckDriver()
    {
        return $this->belongsTo(TruckDriver::class, 'soh_driver');
    }
}
