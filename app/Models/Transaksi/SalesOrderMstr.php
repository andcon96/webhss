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

    public function getNonCancelledSJ()
    {
        return $this->hasMany(SuratJalan::class, 'sj_so_mstr_id', 'id')->where('sj_status','!=','Cancelled');
    }

    public function getOpenOrSelesaiSJ()
    {
        return $this->hasMany(SuratJalan::class, 'sj_so_mstr_id', 'id')
                    ->where('sj_status','Open')
                    ->orWhere('sj_status','Selesai');
    }

    public function getNewSoAttribute()
    {
        // std get NewSo Attribute -> new_so , Ilangin get & Attribute
        return $this->so_status == 'Open';
    }

    public function getNewOpenSoAttribute()
    {
        // std get NewOpenSo Attribute -> new_open_so , Ilangin get & Attribute
        return $this->so_status == 'New' || $this->so_status == 'Open';
    }

    public function getUsedSOAttribute()
    {
        return $this->getDetail->where('sod_qty_ship','>',0)->count();
    }

    protected static function boot()
    {
        parent::boot();

        self::addGlobalScope(function(Builder $builder){
            $builder->orderBy('created_at','DESC');
        });
    }
}
