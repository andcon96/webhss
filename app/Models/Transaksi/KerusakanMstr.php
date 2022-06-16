<?php

namespace App\Models\Transaksi;

use App\Models\Master\KerusakanStrukturDetail;
use App\Models\Master\TruckDriver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanMstr extends Model
{
    use HasFactory;

    public $table = 'kerusakan_mstr';

    public function getDetail()
    {
        return $this->hasMany(KerusakanDetail::class, 'kerusakan_mstr_id');
    }

    public function getTruckDriver()
    {
        return $this->hasOne(TruckDriver::class, 'id' ,'kerusakan_truck_driver');
    }

    public function getMekanik()
    {
        return $this->hasMany(KerusakanStrukturDetail::class, 'kerusakan_mstr_id', 'id');
    }

}
