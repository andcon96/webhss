<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    public $table = 'rute';

    public function getTipe()
    {
        return $this->belongsTo(TipeTruck::class, 'rute_tipe_id', 'id');
    }

    public function getShipFrom()
    {
        return $this->belongsTo(ShipFrom::class, 'rute_shipfrom_id', 'id');
    }

    public function getShipTo()
    {
        return $this->belongsTo(CustomerShipTo::class, 'rute_customership_id', 'id');
    }
}
