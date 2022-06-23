<?php

namespace App\Models\Transaksi;

use App\Models\Master\Truck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SJHistTrip extends Model
{
    use HasFactory;

    public $table = 'sj_trip_hist';
    
    public $timestamps = false;
    
    public function getSJMaster()
    {
        return $this->belongsTo(SuratJalan::class, 'sjh_sj_mstr_id');
    }

    public function getTruck()
    {
        return $this->hasOne(Truck::class,'id','sjh_truck');
    }

}
