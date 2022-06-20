<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerShipTo extends Model
{
    use HasFactory;
    public $table = 'customership';
    protected $fillable = [
        'cs_code',
        'cs_shipto',
        'cs_domain'
    ];
}
