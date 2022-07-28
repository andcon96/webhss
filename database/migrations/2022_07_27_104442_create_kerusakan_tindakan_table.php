<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerusakanTindakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('krt_det', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('krt_krd_id')->index();
            $table->foreign('krt_krd_id')->references('id')->on('krd_det');
            $table->text('krt_remarks');
            $table->date('krt_date');
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
        Schema::dropIfExists('krt_det');
    }
}
