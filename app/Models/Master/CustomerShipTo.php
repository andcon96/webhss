<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerShipTo extends Model
{
    use HasFactory;
    public $table = 'cust_ship';
    protected $fillable = [
        'cust_code',
        'cust_shipto',
        'cust_domain'
    ];
}
