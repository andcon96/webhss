<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicilanHistory extends Model
{
    use HasFactory;

    public $table = 'history_cicilan';

    protected $fillable = ['id'];

    public function getCicilan()
    {
        return $this->belongsTo(Cicilan::class, 'hc_cicilan_id');
    }
}
