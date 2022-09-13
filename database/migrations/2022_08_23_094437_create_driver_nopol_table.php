<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverNopolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_nopol', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dn_driver_id')->index();
            $table->foreign('dn_driver_id')->references('id')->on('driver');
            $table->unsignedBigInteger('dn_truck_id')->index();
            $table->foreign('dn_truck_id')->references('id')->on('truck');
            $table->tinyInteger('dn_is_active')->default(1);
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
        Schema::dropIfExists('driver_nopol');
    }
}
