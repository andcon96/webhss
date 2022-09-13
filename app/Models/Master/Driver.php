<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Driver extends Model
{
    use HasFactory, Sortable;

    public $table = 'driver';

    protected $fillable = ['id'];
    
    public $sortable = ['id','driver_name'];
}
