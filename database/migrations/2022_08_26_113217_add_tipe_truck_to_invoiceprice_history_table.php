<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipeTruckToInvoicepriceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoiceprice_history', function (Blueprint $table) {
            $table->unsignedBigInteger('iph_tipe_truck_id')->after('iph_ip_id')->nullable()->index();
            $table->foreign('iph_tipe_truck_id')->references('id')->on('tipetruck');
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
            $table->dropForeign(['iph_tipe_truck_id']);
            $table->dropColumn('iph_tipe_truck_id');
        });
    }
}
