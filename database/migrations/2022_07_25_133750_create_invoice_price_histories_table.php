<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePriceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoiceprice_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iph_ip_id')->index();
            $table->foreign('iph_ip_id')->references('id')->on('invoiceprice');
            $table->decimal('iph_tonase_price',20,2)->default(0);
            $table->decimal('iph_trip_price',20,2)->default(0);
            $table->tinyInteger('iph_is_active')->default(1);
            $table->date('iph_last_active')->nullable();
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
        Schema::dropIfExists('invoiceprice_history');
    }
}
