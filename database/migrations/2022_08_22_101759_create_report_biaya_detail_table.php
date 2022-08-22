<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportBiayaDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbd_hist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rbd_rb_hist_id')->index();
            $table->foreign('rbd_rb_hist_id')->references('id')->on('rb_hist');
            $table->string('rbd_deskripsi',50);
            $table->decimal('rbd_nominal',12,2)->default(0);
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
        Schema::dropIfExists('report_biaya_detail');
    }
}
