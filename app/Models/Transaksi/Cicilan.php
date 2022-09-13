<?php

namespace App\Models\Transaksi;

use App\Models\Master\DriverNopol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Cicilan extends Model
{
    use HasFactory, Sortable;

    public $table = 'cicilan';

    protected $fillable = ['id'];

    public $sortable = ['id','cicilan_eff_date','cicilan_nominal'];

    public function getDriverNopol()
    {
        return $this->hasOne(DriverNopol::class, 'id', 'cicilan_dn_id');
    }

    public function getTotalPaid()
    {
        return $this->hasMany(CicilanHistory::class, 'hc_cicilan_id');
    }

    public function getTotalPaidActive()
    {
        return $this->hasMany(CicilanHistory::class, 'hc_cicilan_id')->where('hc_is_active',1);
    }

    public function truckNoPolisSortable($query, $direction)
    {
        return $query->join('driver_nopol', 'cicilan.cicilan_dn_id', '=', 'driver_nopol.id')
                    ->join('truck', 'driver_nopol.dn_truck_id', '=', 'truck.id')
                    ->join('driver', 'driver_nopol.dn_driver_id', '=', 'driver.id')
                    ->orderBy('truck_no_polis', $direction)
                    ->select('cicilan.*','truck.truck_no_polis','driver.driver_name');
    }

    public function driverNameSortable($query, $direction)
    {
        return $query->join('driver_nopol', 'cicilan.cicilan_dn_id', '=', 'driver_nopol.id')
                    ->join('truck', 'driver_nopol.dn_truck_id', '=', 'truck.id')
                    ->join('driver', 'driver_nopol.dn_driver_id', '=', 'driver.id')
                    ->orderBy('driver_name', $direction)
                    ->select('cicilan.*','truck.truck_no_polis','driver.driver_name');
    }
}
