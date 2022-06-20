<?php

namespace App\Models\Transaksi;

use App\Models\Master\Truck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportBiaya extends Model
{
    use HasFactory;

    public $table = 'rb_hist';

    public function getTruck()
    {
        return $this->belongsTo(Truck::class, 'id', 'rb_truck_id');
    }
    
}
