<?php

namespace App\Policies;

use App\Models\Master\User;
use App\Models\Transaksi\SalesOrderMstr;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class SalesOrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, SalesOrderMstr $somstr)
    {
        return true;
    }

    public function update(User $user, SalesOrderMstr $somstr)
    {
        return ($somstr->so_status != 'Cancelled' && $somstr->so_status != 'Closed');
    }

    public function delete(User $user, SalesOrderMstr $somstr)
    {
        return  $somstr->so_status == 'Open' && 
                $somstr->getDetail->where('sod_qty_ship','>',0)->count() == 0;
    }  
}
