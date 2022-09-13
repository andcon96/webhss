<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKapalItemCustomerOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_mstr', function (Blueprint $table) {
            $table->string('co_kapal',50)->nullable()->after('co_remark');
            $table->unsignedBigInteger('co_barang_id')->nullable()->index()->after('co_kapal');
            $table->foreign('co_barang_id')->references('id')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
