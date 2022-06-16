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
        return $somstr->so_domain == Session::get('domain');
    }

    public function update(User $user, SalesOrderMstr $somstr)
    {
        return $somstr->so_domain == Session::get('domain');
    }

}
