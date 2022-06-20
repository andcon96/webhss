<?php

namespace App\Models\Transaksi;

use App\Models\Master\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SalesOrderMstr extends Model
{
    use HasFactory;

    public $table = 'so_mstr';

    protected $fillable = ['user_id'];

    public function getDetail()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sod_so_mstr_id');
    }

    public function getSJ()
    {
        return $this->hasMany(SalesOrderSangu::class, 'sos_so_mstr_id');
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, 'cust_code', 'so_cust');
    }

    public function getNewSoAttribute()
    {
        // std get NewSo Attribute -> new_so , Ilangin get & Attribute
        return $this->so_status == 'New';
    }

    public function getNewOpenSoAttribute()
    {
        // std get NewOpenSo Attribute -> new_open_so , Ilangin get & Attribute
        return $this->so_status == 'New' || $this->so_status == 'Open';
    }

    protected static function boot()
    {
        parent::boot();
        
        self::creating(function($model){
            $model->so_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            // $builder->where('user_id', '=', Auth()->user()->id);
            $builder->where('so_domain', Session::get('domain'));
            $builder->orderBy('created_at','DESC');
        });
    }
}
