<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonusIdToSjMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sj_mstr', function (Blueprint $table) {
            $table->unsignedBigInteger('sj_bb_id')->after('sj_default_sangu_type')->nullable()->index();
            $table->foreign('sj_bb_id')->references('id')->on('bonus_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sj_mstr', function (Blueprint $table) {
            $table->dropForeign(['sj_bb_id']);
            $table->dropColumn('sj_bb_id');
        });
    }
}
