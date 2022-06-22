<?php

namespace App\Models\Transaksi;

use App\Models\Master\TruckDriver;
use App\Models\Master\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SJHistTrip extends Model
{
    use HasFactory;

    public $table = 'sj_trip_hist';
    
    public $timestamps = false;
    
    public function getMaster()
    {
        return $this->belongsTo(SalesOrderMstr::class, 'sjh_so_mstr_id');
    }

    public function getSangu()
    {
        return $this->belongsTo(SalesOrderSangu::class, 'sjh_so_mstr_id', 'sos_so_mstr_id');
    }

}
