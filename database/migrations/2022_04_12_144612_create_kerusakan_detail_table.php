<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerusakanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('krd_det', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('krd_kr_mstr_id')->index();
            $table->foreign('krd_kr_mstr_id')->references('id')->on('kr_mstr');
            $table->unsignedBigInteger('krd_kerusakan_id')->index();
            $table->foreign('krd_kerusakan_id')->references('id')->on('kerusakan');
            $table->text('krd_remarks')->nullable();
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
        Schema::dropIfExists('kerusakan_detail');
    }
}
