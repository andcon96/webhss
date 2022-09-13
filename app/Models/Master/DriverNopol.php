<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class DriverNopol extends Model
{
    use HasFactory, Sortable;

    public $table = 'driver_nopol';

    protected $fillable = ['id'];
    
    public $sortable = ['id','dn_truck_id'];

    public function getDriver()
    {
        return $this->hasOne(Driver::class, 'id', 'dn_driver_id');
    }

    public function getTruck()
    {
        return $this->hasOne(Truck::class, 'id', 'dn_truck_id');
    }
}
