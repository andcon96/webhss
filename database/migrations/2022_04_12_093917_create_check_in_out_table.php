<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckInOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkinout', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cio_truck_driver')->index();
            $table->foreign('cio_truck_driver')->references('id')->on('truckdriver')->onDelete('restrict');
            $table->tinyInteger('cio_is_check_in');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkinout');
    }
}
