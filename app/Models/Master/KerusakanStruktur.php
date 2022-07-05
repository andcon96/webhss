<?php

namespace App\Models\Master;

use App\Models\Transaksi\KerusakanDetail;
use App\Models\Transaksi\KerusakanStukturTransaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanStruktur extends Model
{
    use HasFactory;
    
    public $table = 'kerusakan_struktur';

    protected $fillable = [
        'id',
        'ks_order'
    ];

    public function getStrukturTrans(){
        return $this->hasMany(KerusakanStukturTransaksi::class,'krs_kerusakan_struktur_id','id');
    }
}
