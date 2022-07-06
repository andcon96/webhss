<?php

namespace App\Models\Transaksi;

use App\Models\Master\Kerusakan;
use App\Models\Transaksi\KerusakanStukturTransaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanDetail extends Model
{
    use HasFactory;

    public $table = 'krd_det';

    protected $fillable = [
        'id'
    ];

    public function getKerusakan()
    {
        return $this->hasOne(Kerusakan::class, 'id' ,'krd_kerusakan_id');
    }
    
    public function getStrukturTrans()
    {
        return $this->hasMany(KerusakanStukturTransaksi::class, 'krs_krd_det_id', 'id');
    }
}
