<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSjTripHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sj_trip_hist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sjh_sj_mstr_id')->index();
            $table->foreign('sjh_sj_mstr_id')->references('id')->on('sj_mstr')->onDelete('restrict');
            $table->unsignedBigInteger('sjh_truck')->index();
            $table->foreign('sjh_truck')->references('id')->on('truck')->onDelete('restrict');
            $table->string('sjh_remark')->nullable();
            $table->decimal('sjh_uang_extra',20,2)->default(0);
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
        Schema::dropIfExists('so_hist_trip');
    }
}
