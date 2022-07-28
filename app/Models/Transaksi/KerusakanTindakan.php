<?php

namespace App\Models\Transaksi;

use App\Models\Master\KerusakanStrukturDetail;
use App\Models\Master\Truck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanTindakan extends Model
{
    use HasFactory;

    public $table = 'krt_det';

    public function getDetail()
    {
        return $this->hasOne(KerusakanDetail::class, 'id','krt_krd_id');
    }

}
