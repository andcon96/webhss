<?php

namespace App\Models\Transaksi;

use App\Models\Master\GandenganMstr;
use App\Models\Master\KerusakanStrukturDetail;
use App\Models\Master\Truck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanMstr extends Model
{
    use HasFactory;

    public $table = 'kr_mstr';

    public function getDetail()
    {
        return $this->hasMany(KerusakanDetail::class, 'krd_kr_mstr_id');
    }

    public function getTruck()
    {
        return $this->hasOne(Truck::class, 'id' ,'kr_truck')->withoutGlobalScopes();
    }
    public function getGandeng()
    {
        return $this->hasOne(GandenganMstr::class, 'id' ,'kr_gandeng')->withoutGlobalScopes();
    }
    


}
