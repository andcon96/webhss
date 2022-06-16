<?php

namespace App\Models\Transaksi;

use App\Models\Master\Kerusakan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanDetail extends Model
{
    use HasFactory;

    public $table = 'kerusakan_detail';

    protected $fillable = [
        'id'
    ];

    public function getKerusakan()
    {
        return $this->hasOne(Kerusakan::class, 'id' ,'kerusakan_id');
    }
}
