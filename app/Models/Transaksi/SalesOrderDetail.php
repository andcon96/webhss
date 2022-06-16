<?php

namespace App\Models\Transaksi;

use App\Models\Master\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;

    public $table = 'sod_det';

    protected $fillable = [
        'sod_so_mstr_id', 'sod_line', 'sod_part', 'sod_um', 'sod_qty_ord', 'sod_qty_ship',
    ];

    public function getItem()
    {
        return $this->hasOne(Item::class,'item_part','sod_part');
    }
}
