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
            $table->tinyInteger('co_has_bonus')->default('0');
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
        Schema::table('co_mstr', function (Blueprint $table) {
            $table->dropColumn('co_kapal');
            $table->dropColumn('co_has_bonus');
            $table->dropForeign('co_barang_id');
            $table->dropColumn('co_barang_id');
        });
    }
}
