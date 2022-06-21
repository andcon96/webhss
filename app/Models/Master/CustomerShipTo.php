<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CustomerShipTo extends Model
{
    use HasFactory;
    public $table = 'customership';
    protected $fillable = [
        'cs_code',
        'cs_shipto',
        'cs_domain'
    ];
    protected static function boot()
    {
        parent::boot();

        
        self::creating(function($model){
            $model->cust_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            $builder->where('cs_domain', Session::get('domain'));
        });
    }
}
