<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Transaksi\CheckInOut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Truck extends Model
{
    use HasFactory;

    public $table = 'truck';

    public function getAllCheckInOut()
    {
        return $this->hasMany(CheckInOut::class, 'cio_truck')->orderBy('created_at','Desc');
    }
    
    public function getLastCheckInOut()
    {
        return $this->hasOne(CheckInOut::class, 'cio_truck')->latest();
    }

    public function getUserDriver()
    {
        return $this->hasOne(User::class, 'id', 'truck_user_id');
    }

    public function getUserPengurus()
    {
        return $this->hasOne(User::class, 'id', 'truck_pengurus_id');
    }

    public function getTipe()
    {
        return $this->hasOne(TipeTruck::class, 'id', 'truck_tipe_id');
    }
    
    
    protected static function boot()
    {
        parent::boot();

        
        self::creating(function($model){
            $model->truck_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            $builder->where('truck_domain', Session::get('domain'));
        });
    }

}
