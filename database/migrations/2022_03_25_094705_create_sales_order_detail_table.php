<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sod_det', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sod_so_mstr_id')->index();
            $table->foreign('sod_so_mstr_id')->references('id')->on('so_mstr')->onDelete('restrict');
            $table->integer('sod_line');
            $table->string('sod_part',50);
            $table->decimal('sod_qty_ord',15,2);
            $table->decimal('sod_qty_ship',15,2);
            $table->string('sod_um',2);
            $table->date('sod_date')->nullable();
            $table->string('sod_remarks',255)->nullable();
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
        Schema::dropIfExists('sod_det');
    }
}
