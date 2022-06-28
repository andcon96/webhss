<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck', function (Blueprint $table) {
            $table->id();
            $table->string('truck_no_polis');
            $table->unsignedBigInteger('truck_user_id')->index()->nullable();
            $table->foreign('truck_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('truck_pengurus_id')->index();
            $table->foreign('truck_pengurus_id')->references('id')->on('users');
            $table->unsignedBigInteger('truck_tipe_id')->index();
            $table->foreign('truck_tipe_id')->references('id')->on('tipetruck');
            $table->tinyInteger('truck_is_active')->default(1);
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
        Schema::dropIfExists('truck');
    }
}
