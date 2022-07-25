<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Customer extends Model
{
    use HasFactory;
    public $table = 'customer';
    
    protected $fillable = [
        'cust_code',
    ];

    protected static function boot()
    {
        parent::boot();

        
        self::creating(function($model){
            // $model->cust_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            // $builder->where('cust_domain', Session::get('domain'));
        });
    }
}
