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
        return $this->belongsTo(TipeTruck::class, 'id', 'rute_tipe_id');
    }

    public function getShipFrom()
    {
        return $this->belongsTo(ShipFrom::class, 'id', 'rute_shipfrom_id');
    }

    public function getShipTo()
    {
        return $this->belongsTo(CustomerShipTo::class, 'id', 'rute_customership_id');
    }
}
