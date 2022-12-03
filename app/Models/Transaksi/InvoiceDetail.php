<?php

namespace App\Models\Transaksi;

use App\Models\Master\Domain;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_detail';

    protected $fillable = ['id'];


    public function getMaster()
    {
        return $this->belongsTo(InvoiceMaster::class, 'id_im_mstr_id', 'id');
    }

    public function getDomain()
    {
        return $this->hasOne(Domain::class, 'domain_code', 'id_domain');
    }
}
