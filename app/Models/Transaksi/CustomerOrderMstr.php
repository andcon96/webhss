<?php

namespace App\Models\Transaksi;

use App\Models\Master\Barang;
use App\Models\Master\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CustomerOrderMstr extends Model
{
    use HasFactory;

    public $table = 'co_mstr';

    public function getDetail()
    {
        return $this->hasMany(CustomerOrderDetail::class,'cod_co_mstr_id');
    }

    public function getBarang()
    {
        return $this->hasOne(Barang::class,'id','co_barang_id');
    }
    
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, 'cust_code', 'co_cust_code');
    }
    
    public function getNewCoAttribute()
    {
        // std get NewSo Attribute -> new_so , Ilangin get & Attribute
        return $this->co_status == 'New';
    }

    protected static function boot()
    {
        parent::boot();
        
        self::creating(function($model){
            // $model->co_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            // $builder->where('co_domain', Session::get('domain'));
            $builder->orderBy('created_at','DESC');
        });
    }
}
