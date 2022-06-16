<?php

namespace App\Providers;

use App\Models\Master\Role;
use App\Models\Transaksi\SalesOrderMstr;
use App\Policies\SalesOrderPolicy;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // 'App\Models\Transaksi\SalesOrderMstr' => 'App\Policies\SalesOrderPolicy'
        SalesOrderMstr::class => SalesOrderPolicy::class,
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


        Gate::define('access_so', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SO01');
        });

        Gate::define('access_so_sangu', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SO02');
        });

        Gate::define('access_audit_trail_sangu', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SO03');
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

        // DRIVER
        Gate::define('access_drive_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'DR');
        });
        Gate::define('access_check_in_out', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'DR01');
        });

        

        //=============================
        // Menu Master
        //=============================
        Gate::define('access_masters', function ($user) {
            return $user->getRole->role === Role::SUPER_USER;
        });
    }
}