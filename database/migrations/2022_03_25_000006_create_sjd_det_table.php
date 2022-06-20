<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSjdDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sjd_det', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sjd_sj_mstr_id')->index();
            $table->foreign('sjd_sj_mstr_id')->references('id')->on('sj_mstr')->onDelete('restrict');
            $table->integer('sjd_line');
            $table->string('sjd_part');
            $table->decimal('sjd_qty_ship',15,2);
            $table->decimal('sjd_qty_conf',15,2);
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
        Schema::dropIfExists('sjd_det');
    }
}
