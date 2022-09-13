<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bb_barang_id')->index();
            $table->foreign('bb_barang_id')->references('id')->on('barang');
            $table->unsignedBigInteger('bb_tipe_truck_id')->index();
            $table->foreign('bb_tipe_truck_id')->references('id')->on('tipetruck');
            $table->decimal('bb_qty_start',15,2)->default(0);
            $table->decimal('bb_qty_end',15,2)->default(0);
            $table->decimal('bb_price',15,2)->default(0);
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
        Schema::dropIfExists('bonus_barang');
    }
}
