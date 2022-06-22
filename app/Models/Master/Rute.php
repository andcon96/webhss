<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rute extends Model
{
    use HasFactory;

    public $table = 'rute';
    protected $fillable = [
        'rute_tipe_id',
        'rute_shipfrom_id',
        'rute_customership_id'
    ];

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
    
    public function getHistory()
    {
        return $this->hasMany(RuteHistory::class,'history_rute_id','id');
    }

    public function getActivePrice()
    {
        return $this->hasOne(RuteHistory::class, 'history_rute_id')->where('rute_is_active',1);
    }
}
