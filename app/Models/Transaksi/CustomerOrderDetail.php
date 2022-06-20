<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CustomerOrderDetail extends Model
{
    use HasFactory;

    public $table = 'co_mstr';

    public function getItem()
    {
        return $this->hasOne(Item::class,'item_part','cod_part');
    }
}
