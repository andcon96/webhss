<?php

namespace App\Models\Transaksi;

use App\Models\Master\Truck;
use App\Models\Master\TruckDriver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class SalesOrderSangu extends Model
{
    use HasFactory;
    public $table = 'so_sangu';

    protected $fillable = [
        'id'
    ];

    public function getTruckDriver()
    {
        return $this->hasOne(TruckDriver::class, 'id' ,'sos_truck');
    }

    public function getMaster()
    {
        return $this->belongsTo(SalesOrderMstr::class, 'sos_so_mstr_id');
    }
    
    public function countLaporanHist()
    {
        return $this->hasMany(SOHistTrip::class, 'soh_so_mstr_id', 'sos_so_mstr_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::addGlobalScope(function(Builder $builder){
            $builder->whereRelation('getMaster', 'so_domain', Session::get('domain'));
        });
    }
}
