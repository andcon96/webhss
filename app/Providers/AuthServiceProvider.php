<?php

namespace App\Providers;

use App\Models\Master\Role;
use App\Models\Transaksi\CustomerOrderMstr;
use App\Models\Transaksi\KerusakanMstr;
use App\Models\Transaksi\SalesOrderMstr;
use App\Policies\CustomerOrderPolicy;
use App\Policies\KerusakanPolicy;
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
        KerusakanMstr::class => KerusakanPolicy::class,
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

        // Report
        Gate::define('access_report_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'RP');
        });
        Gate::define('access_report', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'RP01');
        });
        
        // Invoice
        Gate::define('access_invoice_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'IV');
        });

        Gate::define('access_invoice', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'IV01');
        });

        // Cicilan
        Gate::define('access_cicilan_side', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'CI');
        });

        Gate::define('access_cicilan', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'CI01');
        });

        Gate::define('access_bayar_cicilan', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'CI02');
        });

        //=============================
        // Settings
        //=============================
        
        //User Maintenance 
        Gate::define('access_usermt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT01');
        });

        //Role Maintenance
        Gate::define('access_rolemt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT02');
        });
        
        //Role Menu maintenance
        Gate::define('access_rolemenumt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT03');
        });

        //Customer Maintenance
        Gate::define('access_custmt', function($user){
            // return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT04');
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MQ01');
        });

        //Ship To Maintenance
        Gate::define('access_stmt', function($user){
            // return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT05');
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MQ02');
        });

        //Ship From maintenance
        Gate::define('access_sfmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT06');
        });

        //Item Maintenance
        Gate::define('access_itemmt', function($user){
            // return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT07');
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MQ03');
        });

        //Kerusakan Maintenance
        Gate::define('access_krmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT08');
        });

        //Struktur Kerusakan maintenance
        Gate::define('access_skmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT09');
        });

        //Truck maintenance
        Gate::define('access_trmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT10');
        });

        //Tipe Truck maintenance
        Gate::define('access_ttmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT11');
        });

        //Prefix Maintenance
        Gate::define('access_pmmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT12');
        });

        //WSA Qxtend Maintenance
        Gate::define('access_wqmt', function($user){
            // return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT13');
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MQ04');
        });

        //rute maintenance
        Gate::define('access_rutemt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT14');
        });

        //Invoice Price maintenance
        Gate::define('access_ipmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT15');
        });

        //Approval Maintenance
        Gate::define('access_apmt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT16');
        });

        //Barang
        Gate::define('access_barang', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT17');
        });

        //Bonus Barang
        Gate::define('access_bonus_barang', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT18');
        });

        //Driver
        Gate::define('access_driver', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT19');
        });

        //Driver Nopol
        Gate::define('access_driver_nopol', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT20');
        });
        //Driver Nopol
        Gate::define('access_gandengan', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT21');
        });

        //=============================
        // Menu Master
        //=============================
        Gate::define('access_masters', function ($user) {
            return $user->getRole->role === Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MT');
        });

        Gate::define('access_masters_qad', function ($user){
            return $user->getRole->role === Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MQ');
        });
    }
}