<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeTruck extends Model
{
    use HasFactory;
    
    public $table = 'tipetruck';
    protected static function boot()
    {
        parent::boot();

        
        self::creating(function($model){
            $model->tt_isactive = 1;
        });
    }
}
