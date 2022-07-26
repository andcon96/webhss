<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePriceHistory extends Model
{
    use HasFactory;

    public $table = 'invoiceprice_history';
    
    public function getIP()
    {
        return $this->belongsTo(InvoicePrice::class, 'iph_ip_id', 'id');
    }
}
