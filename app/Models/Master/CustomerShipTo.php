<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerShipTo extends Model
{
    use HasFactory;
    public $table = 'customership';
    protected $fillable = [
        'cs_cust_code',
        'cs_shipto',
        'cs_shipto_name',
        'cs_address'
    ];
    protected static function boot()
    {
        parent::boot();

        
        // self::creating(function($model){
        //     $model->cs_domain = Session::get('domain');
        // });

        self::addGlobalScope(function(Builder $builder){
            // $builder->where('cs_domain', Session::get('domain'));
        });
    }
}
