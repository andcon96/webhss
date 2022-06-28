<?php

namespace App\Policies;

use App\Models\Master\User;
use App\Models\Transaksi\SuratJalan;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class SuratJalanPolicy
{
    use HandlesAuthorization;

    public function view(User $user, SuratJalan $sjmstr)
    {
        return $sjmstr->sj_domain == Session::get('domain');
    }

    public function update(User $user, SuratJalan $sjmstr)
    {
        return $sjmstr->sj_domain == Session::get('domain');
    }
}
