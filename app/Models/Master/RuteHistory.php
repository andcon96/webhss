<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteHistory extends Model
{
    
    use HasFactory;
    protected $table = 'rute_history';
    public function getRute()
    {
        return $this->belongsTo(Rute::class, 'history_rute_id', 'id');
        
    }
}
