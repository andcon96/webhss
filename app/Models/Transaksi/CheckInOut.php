<?php

namespace App\Models\Transaksi;

use App\Models\Master\TruckDriver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInOut extends Model
{
    use HasFactory;

    public $table = 'checkinout';

    public $timestamps = false;

    public function getTruckDriver()
    {
        return $this->belongsTo(TruckDriver::class,'cio_truck_driver');
    }
}
