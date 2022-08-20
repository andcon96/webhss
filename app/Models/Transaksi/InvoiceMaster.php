<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceMaster extends Model
{
    use HasFactory;

    protected $table = 'invoice_master';

    public function getDetail()
    {
        return $this->hasMany(InvoiceDetail::class, 'id_im_mstr_id');
    }

    public function getSalesOrder()
    {
        return $this->hasOne(SalesOrderMstr::class, 'id', 'im_so_mstr_id');
    }

}
