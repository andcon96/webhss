<?php

namespace App\Models\Transaksi;

use App\Models\Master\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratJalanDetail extends Model
{
    use HasFactory;

    public $table = 'sjd_det';
    
    protected $fillable = ['id'];

    public function getItem()
    {
        return $this->hasOne(Item::class,'item_part','sjd_part');
    }
}
