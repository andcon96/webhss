<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class approval extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'approval_name', 'approval_email'
    ];
}
