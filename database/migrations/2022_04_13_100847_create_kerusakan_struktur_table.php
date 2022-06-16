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
        Schema::create('kerusakan_struktur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kerusakan_mstr_id')->index();
            $table->foreign('kerusakan_mstr_id')->references('id')->on('kerusakan_mstr')->onDelete('restrict');
            $table->unsignedBigInteger('kerusakan_struktur_id')->index();
            $table->foreign('kerusakan_struktur_id')->references('id')->on('struktur_lapor_kerusakan')->onDelete('cascade');
            $table->string('kerusakan_mekanik');
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
        Schema::dropIfExists('kerusakan_struktur');
    }
}
