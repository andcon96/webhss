<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoiceprice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ip_cust_id')->index();
            $table->foreign('ip_cust_id')->references('id')->on('customer');
            $table->unsignedBigInteger('rute_shipfrom_id')->index()->nullable();
            $table->foreign('rute_shipfrom_id')->references('id')->on('shipfrom');
            $table->unsignedBigInteger('rute_customership_id')->index();
            $table->foreign('rute_customership_id')->references('id')->on('customership');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoiceprice');
    }
}
