<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportBiayaDetail extends Model
{
    use HasFactory;

    public $fillable = ['id'];

    protected $table = 'rbd_hist';
}
