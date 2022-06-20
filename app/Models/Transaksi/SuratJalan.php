<?php

namespace App\Models\Transaksi;

use App\Models\Master\Truck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class SuratJalan extends Model
{
    use HasFactory;

    public $table = 'sj_mstr';

    public function getDetail()
    {
        return $this->hasMany(SuratJalanDetail::class, 'sjd_sj_mstr_id');
    }

    public function getSOMaster()
    {
        return $this->belongsTo(SalesOrderMstr::class, 'id', 'sj_so_mstr_id');
    }

    public function getTruck()
    {
        return $this->hasOne(Truck::class,'id','sj_truck_id');
    }
    
    protected static function boot()
    {
        parent::boot();
        
        self::creating(function($model){
            $model->sj_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            $builder->where('sj_domain', Session::get('domain'));
            $builder->orderBy('created_at','DESC');
        });
    }
}
