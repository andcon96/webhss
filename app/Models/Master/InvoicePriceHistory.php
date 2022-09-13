<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePriceHistory extends Model
{
    use HasFactory;

    public $table = 'invoiceprice_history';

    protected $fillable = [
        'iph_ip_id',
        'iph_is_active',
        'iph_last_active'
    ];
    
    public function getIP()
    {
        return $this->belongsTo(InvoicePrice::class, 'iph_ip_id', 'id');
    }

    public function getTipeTruck()
    {
        return $this->belongsTo(TipeTruck::class, 'iph_tipe_truck_id', 'id');
    }
}
