<?php

namespace App\Models\Master;

use App\Models\Transaksi\KerusakanDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanStrukturDetail extends Model
{
    use HasFactory;
    
    public $table = 'kerusakan_struktur';

    protected $fillable = [
        'kerusakan_struktur_id',
        'kerusakan_mstr_id',
        'kerusakan_struktur_detail_id'
    ];

    public function getDetail()
    {
        return $this->hasOne(KerusakanDetail::class, 'id', 'kerusakan_struktur_detail_id');
    }

    public function getStruktur()
    {
        return $this->hasOne(StrukturKerusakan::class, 'id', 'kerusakan_struktur_id');
    }
}
