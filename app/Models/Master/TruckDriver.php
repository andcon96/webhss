<?php

namespace App\Models\Master;

use App\Models\Transaksi\CheckInOut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckDriver extends Model
{
    use HasFactory;

    public $table = 'truckdriver';

    public function getUser()
    {
        return $this->belongsTo(User::class, 'truck_user_id');
    }

    public function getTruck()
    {
        return $this->belongsTo(Truck::class, 'truck_no_polis');
    }

    public function getAllCheckInOut()
    {
        return $this->hasMany(CheckInOut::class, 'cio_truck_driver')->orderBy('created_at','Desc');
    }
    
    public function getLastCheckInOut()
    {
        return $this->hasOne(CheckInOut::class, 'cio_truck_driver')->latest();
    }
}
