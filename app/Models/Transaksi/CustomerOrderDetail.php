<?php

namespace App\Models\Transaksi;

use App\Models\Master\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderDetail extends Model
{
    use HasFactory;

    public $table = 'cod_det';
    
    protected $fillable = [
        'id'
    ];

    public function getItem()
    {
        return $this->hasOne(Item::class,'item_part','cod_part');
    }
}
