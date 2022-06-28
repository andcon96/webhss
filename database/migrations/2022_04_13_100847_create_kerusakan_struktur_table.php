<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerusakanStrukturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kr_struktur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('krs_krd_det_id')->index();
            $table->foreign('krs_krd_det_id')->references('id')->on('krd_det');
            $table->unsignedBigInteger('krs_kerusakan_struktur_id')->index();
            $table->foreign('krs_kerusakan_struktur_id')->references('id')->on('kerusakan_struktur');
            $table->string('krs_desc',255);
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
        
        Schema::dropIfExists('kr_struktur');
    }
}
