<?php

namespace App\Providers;

use App\Models\Master\Role;
use App\Models\Transaksi\CustomerOrderMstr;
use App\Models\Transaksi\SalesOrderMstr;
use App\Policies\CustomerOrderPolicy;
use App\Policies\SalesOrderPolicy;
use App\Policies\SuratJalanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        SalesOrderMstr::class => SalesOrderPolicy::class,
        CustomerOrderMstr::class => CustomerOrderPolicy::class,
        SuratJalan::class => SuratJalanPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('super_user', function ($user) {
            return $user->getRole->role === Role::SUPER_USER;
        });

        Gate::define('user', function ($user) {
            return $user->getRole->role === Role::User;
        });


        //============================
        // Dashboard
        //============================

        Gate::define('access_dashboard', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'HO01') ||
                $user->getRole->role === Role::SUPER_USER;
        });


        //=============================
        // Menu Transaksi
        //=============================
        
        // SO
        Gate::define('access_so_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SO');
        });
        
        Gate::define('access_co', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SO04');
        });

        Gate::define('access_sj', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SO05');
        });

        Gate::define('access_so', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SO01');
        });

        // TRIP
        Gate::define('access_trip_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'TR');
        });

        Gate::define('access_trip', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'TR01');
        });

        Gate::define('access_lapor_trip', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'TR02');
        });

        Gate::define('access_lapor_sj', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'TR03');
        });

        Gate::define('access_lapor_kerusakan', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'TR04');
        });

        Gate::define('access_rb', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'TR05');
        });

        // DRIVER
        Gate::define('access_drive_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'DR');
        });
        Gate::define('access_check_in_out', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'DR01');
        });

        // DRIVER
        Gate::define('access_report_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'RP');
        });
        Gate::define('access_report', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'RP01');
        });


        

        //=============================
        // Menu Master
        //=============================
        Gate::define('access_masters', function ($user) {
            return $user->getRole->role === Role::SUPER_USER;
        });
    }
}