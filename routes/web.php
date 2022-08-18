<?php

use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\AccessRoleMenuController;
use App\Http\Controllers\Master\ApprovalController;
use App\Http\Controllers\Master\CustomerController;
use App\Http\Controllers\Master\CustomerShipToController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\DomainController;
use App\Http\Controllers\Master\InvoicePriceController;
use App\Http\Controllers\Master\ItemMTController;
use App\Http\Controllers\Master\KerusakanController;
use App\Http\Controllers\Master\PrefixController;
use App\Http\Controllers\Master\RoleMTController;
use App\Http\Controllers\Master\UserMTController;
use App\Http\Controllers\Master\QxWsaMTController;
use App\Http\Controllers\Master\RuteController;
use App\Http\Controllers\Master\ShipFromController;
use App\Http\Controllers\Master\StrukturKerusakanController;
use App\Http\Controllers\Master\TruckMTController;
use App\Http\Controllers\Master\TruckTipeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Transaksi\BrowsePolisController;
use App\Http\Controllers\Transaksi\CheckInOutController;
use App\Http\Controllers\Transaksi\CustomerOrderController;
use App\Http\Controllers\Transaksi\GenerateReportController;
use App\Http\Controllers\Transaksi\InvoiceMTController;
use App\Http\Controllers\Transaksi\KerusakanLaporMTController;
use App\Http\Controllers\Transaksi\ReportBiayaController;
use App\Http\Controllers\Transaksi\SalesOrderController;
use App\Http\Controllers\Transaksi\SuratJalanController;
use App\Http\Controllers\Transaksi\SuratJalanLaporMTController;
use App\Http\Controllers\Transaksi\TripLaporMTController;
use App\Http\Controllers\Transaksi\TripMTController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Redirect::to('home');
    }
    return view('auth.login');
});

Auth::routes();

// Route::get('loadrutefirst',[RuteController::class,'loadrutefirst']);
// Route::get('loadhistoryrute',[RuteController::class,'loadhistoryrute']);
// Route::get('loadinvoicefirst',[InvoiceMTController::class,'loadinvoicefirst']);
// Route::get('loadinvoice',[InvoiceMTController::class,'loadinvoice']);
// Route::get('loadhistoryrutedetail',[RuteController::class,'loadhistoryrutedetail']);

Route::group(['middleware' => ['auth']], function () {
    //================================
    // Logout & Home 123
    //================================
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::get('Changepass', [ChangePassword::class,'update'])->name('changepass');
    //================================
    

    //================================
    // Notification
    //================================
    Route::post('/mark-as-read', [NotificationController::class, 'notifread'])->name('notifread');
    Route::post('/mark-all-as-read', [NotificationController::class, 'notifreadall'])->name('notifreadall');
    Route::get('/changedomain', [NotificationController::class, 'changedomain'])->name('changeDomain');
    
    //================================

    Route::group(['middleware'=>'can:access_co'], function(){
        Route::get('/customerorder/getdetail/{id}', [CustomerOrderController::class, 'getdetail'])->name('getDetailCO');
        Route::get('/customerorder/getalokasi/{id}', [CustomerOrderController::class, 'getalokasi'])->name('getAlokasiCO');
        Route::get('/customerorder/createso/{id}', [CustomerOrderController::class, 'createso'])->name('coCreateSO');
        Route::post('/customerorder/saveso', [CustomerOrderController::class, 'updateso'])->name('coUpdateSO');
        Route::resource('customerorder',CustomerOrderController::class);
    });

    Route::group(['middleware'=>'can:access_report'],function(){
        Route::get('/report/sangubymonth', [GenerateReportController::class, 'reportsangu'])->name('reportSangu');
        Route::get('/report/updatepreview', [GenerateReportController::class, 'updatepreview'])->name('updatePreview');
        Route::get('/report/printpdf', [GenerateReportController::class, 'printpdf'])->name('reportPerNopol');
        Route::resource('report', GenerateReportController::class);
    });

    Route::group(['middleware'=>'can:access_rb'], function(){
        Route::resource('rbhist', ReportBiayaController::class);
    });

    Route::group(['middleware'=>'can:access_sj'], function(){
        Route::get('/suratjalan/socreatesj', [SuratJalanController::class, 'socreatesj'])->name("soCreateSJ");
        Route::get('/suratjalan/createsj', [SuratJalanController::class, 'createsj'])->name("CreateSJ");
        Route::get('/suratjalan/getdetail/{id}', [SuratJalanController::class, 'getdetail'])->name('getDetailSJ');
        Route::get('getrute', [SuratJalanController::class, 'getrute'])->name('getRute');
        Route::get('/detailso/{id}', [SuratJalanController::class, 'getdetailso'])->name('getDetailSJSO');
        Route::resource('suratjalan',SuratJalanController::class);
    });

    Route::group(['middleware'=>'can:access_so'], function(){
        Route::get('/salesorder/getco', [SalesOrderController::class, 'getco'])->name('getCO');
        Route::get('/salesorder/getalokasi/{id}', [SalesOrderController::class, 'getalokasiso'])->name('getAlokasiSO');
        Route::get('/salesorder/getdetail/{id}', [SalesOrderController::class, 'getdetail'])->name('getDetailSO');
        Route::resource('salesorder', SalesOrderController::class);
        Route::get('/getum', [SalesOrderController::class, 'getUM'])->name('getum');
        Route::get('/getshipto', [SalesOrderController::class, 'getshipto'])->name('getShipTo');
    });

    Route::group(['middleware'=>'can:access_trip'], function(){
        Route::resource('tripmt', TripMTController::class);
    });

    Route::group(['middleware'=>'can:access_no_polis'], function(){
        Route::resource('browsepolis', BrowsePolisController::class);
    });

    Route::group(['middleware'=>'can:access_lapor_trip'], function(){
        Route::resource('laportrip', TripLaporMTController::class);
    });

    Route::group(['middleware'=>'can:access_lapor_sj'], function(){
        Route::get('laporsj/{sj}/{truck}', [SuratJalanLaporMTController::class, 'laporsj'])->name('LaporSJ');
        Route::post('laporsj/updatesj', [SuratJalanLaporMTController::class, 'updatesj'])->name('updateSJ');
        Route::get('laporsj/catatsj/{sj}/{truck}', [SuratJalanLaporMTController::class, 'catatsj'])->name('CatatSJ');
        Route::post('laporsj/catatsj', [SuratJalanLaporMTController::class, 'updatecatatsj'])->name('updateCatatSJ');
        Route::resource('laporsj', SuratJalanLaporMTController::class);
    });

    Route::group(['middleware'=>'can:access_lapor_kerusakan'], function(){
        Route::resource('laporkerusakan', KerusakanLaporMTController::class);
        Route::get('laporkerusakan/assignkr/{id}', [KerusakanLaporMTController::class, 'assignkr'])->name('assignKR');
        Route::put('laporkerusakan/upassignkr/{id}', [KerusakanLaporMTController::class, 'upassignkr'])->name('UpAssignKR');
        Route::get('laporkerusakan/assingremarkskr/{id}', [KerusakanLaporMTController::class, 'assingremarkskr'])->name('assignRemarks');
        Route::put('laporkerusakan/upassignremarkskr/{id}', [KerusakanLaporMTController::class, 'upassignremarkskr'])->name('UpAssignRemarks');
        Route::get('laporkerusakan/krhistory/{id}', [KerusakanLaporMTController::class, 'krhistoryview'])->name('krhistview');
        // Route::get('laporkerusakan/krhistory/{id}', [KerusakanLaporMTController::class, 'krhistorytable'])->name('krhisttable');
    });

    Route::group(['middleware'=>'can:access_check_in_out'], function(){
        Route::resource('checkinout', CheckInOutController::class);
    }); 
    
    Route::group(['middleware'=>'can:access_invoice'], function(){
        Route::resource('invoicemt', InvoiceMTController::class);
        Route::get('checkinvoice', [InvoiceMTController::class, 'checkinvoice'])->name('checkInvoice');
        Route::get('printinvoice/{id}', [InvoiceMTController::class, 'printinvoice'])->name('printInvoice');
    });

    Route::group(['middleware'=>'can:access_masters'], function () {
        Route::group(['middleware'=>'can:access_usermt'], function () {
        //================================
        // User Maintenance
        //================================
        Route::resource('usermaint', UserMTController::class);
        Route::get('/user/getdata', [UserMTController::class, 'index']);
        Route::get('/searchoptionuser', [UserMTController::class, 'searchoptionuser']);
        Route::post('/adminchangepass', [UserMTController::class, 'adminchangepass']);
        });
        //================================

        //================================
        // Role Maintenance
        //================================
        Route::group(['middleware'=>'can:access_rolemt'], function () {
            Route::resource('rolemaint', RoleMTController::class);
        });
        //================================

        //================================
        // Access Role Menu
        //================================
        Route::group(['middleware'=>'can:access_rolemenumt'], function () {
            Route::resource('accessrolemenu', AccessRoleMenuController::class);
            Route::get('/accessmenu', [AccessRoleMenuController::class, 'accessmenu'])->name('accessmenu');
        });
        //================================

        //================================
        // Truck Maintenance
        //================================
        Route::group(['middleware'=>'can:access_trmt'], function () {
            Route::resource('truckmaint', TruckMTController::class);
        });
        //================================
        
        //================================
        // Kerusakan Maintenance
        //================================
        Route::group(['middleware'=>'can:access_krmt'], function () {
            Route::resource('kerusakanmt', KerusakanController::class);
        });
        //================================

        //================================
        // Struktur Kerusakan Maintenance
        //================================
        Route::group(['middleware'=>'can:access_stmt'], function () {
            Route::resource('strukturkerusakanmt', StrukturKerusakanController::class);
            Route::get('activestruc/{id}', [StrukturKerusakanController::class,'activestruc']);
        });
        //================================

        //================================
        // Customer Maintenance
        //================================
        Route::group(['middleware'=>'can:access_custmt'], function () {
            Route::resource('customermaint', CustomerController::class);
        });
        //================================

        //================================
        // Item Maintenance
        //================================
        Route::group(['middleware'=>'can:access_itemmt'], function () {
            Route::resource('itemmaint', ItemMTController::class);
        });
        //================================

        //================================
        // Prefix Maintenance
        //================================
        Route::group(['middleware'=>'can:access_pmmt'], function () {
            Route::resource('prefixmaint', PrefixController::class);
        });
        //================================

        //================================
        // Department Maintenance
        //================================
        Route::resource('deptmaint', DepartmentController::class);
        //================================

        //================================
        // Domain Maintenance
        //================================
        Route::resource('domainmaint', DomainController::class);
        //================================
        
        // QX WSA Master
        //================================
        Route::group(['middleware'=>'can:access_wqmt'], function () {
            Route::resource('qxwsa', QxWsaMTController::class);
        });
        //================================
        
        // Ship To Master
        //================================
        Route::group(['middleware'=>'can:access_stmt'], function () {
            Route::resource('custshipto', CustomerShipToController::class);
        });
        //================================
        
        // Ship From Master
        //================================
        Route::group(['middleware'=>'can:access_sfmt'], function () {
            Route::resource('shipfrom', ShipFromController::class);
        });
        //================================

        // Rute Maintenance
        //================================
        Route::group(['middleware'=>'can:access_rutemt'], function () {
            Route::resource('rute', RuteController::class);
            Route::get('rute/rutedetail/{id}', [RuteController::class,'viewDetail']);
            Route::get('rute/rutedetail/{oldid}/historydetail/{id}', [RuteController::class,'viewHistory']);
            Route::get('downloadexcel', [RuteController::class,'downloadtemplate']);
            Route::post('importexcel', [RuteController::class,'importexcel']);
            Route::post('importrute', [RuteController::class,'importrute']);
            Route::post('newrute', [RuteController::class,'newrute']);
            Route::post('changestatus', [RuteController::class,'historychangestatus'])->name('changestatus');
        });
        //================================

        // Invoice Price Maintenance
        //================================
        Route::group(['middleware'=>'can:access_ipmt'], function () {
            Route::resource('invoiceprice', InvoicePriceController::class);
            Route::get('invoiceprice/invoicepricedetail/{detailid}/historydetail/{id}', [InvoicePriceController::class,'viewHistory']);
            Route::get('invoiceprice/invoicepricedetail/{id}', [InvoicePriceController::class,'listdetail']);
            Route::post('newinvoiceprice', [InvoicePriceController::class,'newinvoiceprice']);
        });

        //================================


        // Approval Maintenance
        //================================
        Route::group(['middleware'=>'can:access_apmt'], function () {
            Route::resource('approvalmt', ApprovalController::class);
            Route::get('/approvalmt/getdata', [ApprovalController::class, 'index']);
        });
        //================================

        // Tipe Truck Maintenance
        //================================
        Route::group(['middleware'=>'can:access_ttmt'], function () {
            Route::resource('tipetruck', TruckTipeController::class);
        });
        //================================
    });
});
