<?php

namespace App\Models\Master;

use App\Models\Transaksi\KerusakanDetail;
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
}
