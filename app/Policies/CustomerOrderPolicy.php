<?php

namespace App\Policies;

use App\Models\Master\User;
use App\Models\Transaksi\CustomerOrderMstr;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class CustomerOrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, CustomerOrderMstr $comstr)
    {
        return $comstr->so_domain == Session::get('domain');
    }

    public function update(User $user, CustomerOrderMstr $comstr)
    {
        return $comstr->so_domain == Session::get('domain');
    }

}
