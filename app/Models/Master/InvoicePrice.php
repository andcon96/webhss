<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePrice extends Model
{
    use HasFactory;

    public $table = 'invoiceprice';

    protected $fillable = ['id','ip_cust_id','ip_shipfrom_id','ip_customership_id'];

    public function getShipFrom()
    {
        return $this->belongsTo(ShipFrom::class, 'ip_shipfrom_id', 'id');
    }

    public function getShipTo()
    {
        return $this->belongsTo(CustomerShipTo::class, 'ip_customership_id', 'id');
    }

    public function getCustomer()
    {
        return $this->belongsTo(Customer::class, 'ip_cust_id', 'id');
    }
    
    public function getHistory()
    {
        return $this->hasMany(InvoicePriceHistory::class,'iph_ip_id','id');
    }
    
    public function getActivePrice()
    {
        return $this->hasOne(InvoicePriceHistory::class, 'iph_ip_id')->where('iph_is_active',1);
    }
}
