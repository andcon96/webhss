<?php

namespace App\Models\Transaksi;

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
}
