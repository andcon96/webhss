<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cod_det', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cod_co_mstr_id')->index();
            $table->foreign('cod_co_mstr_id')->references('id')->on('co_mstr')->onDelete('restrict');
            $table->integer('cod_line');
            $table->string('cod_part');
            $table->decimal('cod_qty_ord',15,2);
            $table->decimal('cod_qty_used',15,2);
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
        Schema::dropIfExists('cod_det');
    }
}
