<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Transaksi\CheckInOut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Truck extends Model
{
    use HasFactory, Sortable;

    public $table = 'truck';
    protected $fillable = [
        'truck_domain',
        'truck_no_polis',
        'truck_user_id',
        'truck_pengurus_id'
    ];
    public $sortable = ['id','truck_no_polis'];

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

    public function getDomain()
    {
        return $this->hasOne(Domain::class, 'domain_code', 'truck_domain');
    }
    
    
    protected static function boot()
    {
        parent::boot();
        
        self::creating(function($model){
            // $model->truck_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            // $builder->where('truck_domain', Session::get('domain'));
            $builder->where('truck_is_active',1);
        });
    }

}
