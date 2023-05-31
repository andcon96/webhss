<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDecimalToInvoicepriceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoiceprice_history', function (Blueprint $table) {
            $table->decimal('iph_tonase_price','20','3')->nullable()->change();
            $table->decimal('iph_trip_price','20','3')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoiceprice_history', function (Blueprint $table) {
            $table->decimal('iph_tonase_price','20','2')->nullable()->change();
            $table->decimal('iph_trip_price','20','2')->nullable()->change();
        });
    }
}
