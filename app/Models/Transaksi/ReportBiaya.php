<?php

namespace App\Models\Transaksi;

use App\Models\Master\Truck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class ReportBiaya extends Model
{
    use HasFactory;

    public $table = 'rb_hist';

    public function getTruck()
    {
        return $this->belongsTo(Truck::class, 'rb_truck_id', 'id');
    }
    
    
    protected static function boot()
    {
        parent::boot();
        
        self::creating(function($model){
            $model->rb_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            // $builder->where('user_id', '=', Auth()->user()->id);
            $builder->where('rb_domain', Session::get('domain'));
            $builder->orderBy('created_at','DESC');
        });
    }
}
