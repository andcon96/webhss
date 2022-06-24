<?php

namespace App\Models\Master;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class RuteHistory extends Model
{
    
    use HasFactory;
    protected $table = 'rute_history';
    public function getRute()
    {
        return $this->belongsTo(Rute::class, 'history_rute_id', 'id');
        
    }
    
    protected static function boot()
    {
        parent::boot();

        
        self::creating(function($model){
            $model->history_user = Auth::id();
        });

        // self::addGlobalScope(function(Builder $builder){
        //     $builder->where('cust_domain', Session::get('domain'));
        // });
    }
    
}
