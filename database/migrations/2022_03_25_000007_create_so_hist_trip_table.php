<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoHistTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_hist_trip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soh_so_mstr_id')->index();
            $table->foreign('soh_so_mstr_id')->references('id')->on('so_mstr')->onDelete('restrict');
            $table->unsignedBigInteger('soh_truck_id')->index();
            $table->foreign('soh_truck_id')->references('id')->on('truck')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('so_hist_trip');
    }
}
