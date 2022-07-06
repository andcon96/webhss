<?php

namespace App\Models\Transaksi;

use App\Models\Master\KerusakanStruktur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanStukturTransaksi extends Model
{
    use HasFactory;
    
    public $table = 'kr_struktur';
    public function getStrukturMaster()
    {
        return $this->belongsTo(KerusakanStruktur::class, 'krs_kerusakan_struktur_id','id');
    }
}
