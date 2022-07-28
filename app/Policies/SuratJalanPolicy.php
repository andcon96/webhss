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
        // return $sjmstr->sj_domain == Session::get('domain');
        return true;
    }

    public function update(User $user, SuratJalan $sjmstr)
    {
        // return $sjmstr->sj_domain == Session::get('domain');
        return true;
    }

    public function delete(User $user, SuratJalan $sjmstr)
    {
        // return $sjmstr->sj_domain == Session::get('domain') && $sjmstr->sj_status == 'Open';
        return $sjmstr->sj_status == 'Open';
    }
}
