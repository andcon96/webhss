<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusBarang extends Model
{
    use HasFactory;

    public $table =  'bonus_barang';

    protected $fillable = ['id'];

    public function getBarang()
    {
        return $this->hasOne(Barang::class, 'id', 'bb_barang_id');
    }

    public function getTipeTruck()
    {
        return $this->hasOne(TipeTruck::class, 'id', 'bb_tipe_truck_id');
    }
}
