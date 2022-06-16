<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturKerusakan extends Model
{
    use HasFactory;

    public $table = 'struktur_lapor_kerusakan';

    protected $fillable = [
        'slk_order',
    ];
}
