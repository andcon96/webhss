<?php

namespace App\Models\Transaksi;

use App\Models\Master\CustomerShipTo;
use App\Models\Master\ShipFrom;
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

    public function getCOMaster()
    {
        return $this->hasOne(CustomerOrderMstr::class, 'id' , 'so_co_mstr_id');
    }

    public function getShipFrom()
    {
        return $this->belongsTo(ShipFrom::class, 'so_ship_from', 'sf_code');
    }

    public function getShipTo()
    {
        return $this->belongsTo(CustomerShipTo::class, 'so_ship_to', 'cs_shipto');
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
