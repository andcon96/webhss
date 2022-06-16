<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlokasiSoSanguTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_sangu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sos_so_mstr_id')->index();
            $table->foreign('sos_so_mstr_id')->references('id')->on('so_mstr')->onDelete('restrict');
            $table->unsignedBigInteger('sos_truck')->index();
            $table->foreign('sos_truck')->references('id')->on('truckdriver')->onDelete('restrict');
            $table->string('sos_sangu');
            $table->integer('sos_tot_trip');
            $table->enum('so_status',['Open','Selesai','Closed'])->default('Open');
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
        Schema::dropIfExists('so_sangu');
    }
}
