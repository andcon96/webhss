<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Item extends Model
{
    use HasFactory;
    public $table = 'item';
    
    protected $fillable = [
        'item_part',
    ];

    
    protected static function boot()
    {
        parent::boot();

        
        self::creating(function($model){
            $model->item_domain = Session::get('domain');
        });

        self::addGlobalScope(function(Builder $builder){
            $builder->where('item_domain', Session::get('domain'));
        });
    }
}
