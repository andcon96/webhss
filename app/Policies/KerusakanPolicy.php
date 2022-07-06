<?php

namespace App\Policies;

use App\Models\Master\User;
use App\Models\Transaksi\KerusakanMstr;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class KerusakanPolicy
{
    
    /**
     * Create a new policy instance.
     *
     * @return void
     */

    use HandlesAuthorization;

    public function view(User $user)
    {
        return Session::get('domain') == 'HSS' ;
    }
    public function update(User $user, KerusakanMstr $krmstr)
    {
        return Session::get('domain') == 'HSS' && ($krmstr->kr_status == 'New');
    }
    public function delete(User $user)
    {
        return Session::get('domain') == 'HSS';
    }
    public function create(User $user)
    {
        return Session::get('domain') == 'HSS';
    }
    
    public function custompolicy(User $user)
    {
        return Session::get('domain') == 'HSS';
    }

}
