<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    public $table = 'barang';

    protected $fillable = ['id'];
    
    public function hasBonus()
    {
        return $this->hasMany(BonusBarang::class, 'bb_barang_id');
    }
}
