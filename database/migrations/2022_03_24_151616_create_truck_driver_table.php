<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truckdriver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('truck_no_polis')->index();
            $table->foreign('truck_no_polis')->references('id')->on('truck')->onDelete('restrict');
            $table->unsignedBigInteger('truck_user_id')->index();
            $table->foreign('truck_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->tinyInteger('truck_is_active')->default('1');
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
        Schema::dropIfExists('truckdriver');
    }
}
