<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCustomer extends Model
{
    use HasFactory;

    public $table = 'bank_customer';

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, 'id', 'bc_customer_id');
    }

    public function getDomain()
    {
        return $this->hasOne(Domain::class, 'id', 'bc_domain_id');
    }
}
