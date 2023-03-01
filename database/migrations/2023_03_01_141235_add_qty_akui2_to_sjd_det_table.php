<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyAkui2ToSjdDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sjd_det', function (Blueprint $table) {
            $table->decimal('sjd_qty_akui', 10, 2)->default(0)->after('sjd_qty_conf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sjd_det', function (Blueprint $table) {
            $table->dropColumn('sjd_qty_akui');
        });
    }
}
