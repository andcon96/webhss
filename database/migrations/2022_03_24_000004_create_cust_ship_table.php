<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustShipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customership', function (Blueprint $table) {
            $table->id();
            $table->string('cs_domain');
            $table->unsignedBigInteger('cs_cust_code')->index();
            $table->foreign('cs_cust_code')->references('cust_code')->on('customer');
            $table->string('cs_shipto');
            $table->string('cs_shipto_name',255)->nullable();
            $table->text('cs_address')->nullable();
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
        Schema::dropIfExists('cust_ship');
    }
}
