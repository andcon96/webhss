<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GandenganMstr extends Model
{
    use HasFactory;
    public $table = 'gandengan_mstr';
    public function getDomain()
    {
        return $this->belongsTo(Domain::class, 'gandeng_domain','id');;
    }
}
