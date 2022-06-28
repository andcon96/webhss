<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipeToRbHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rb_hist', function (Blueprint $table) {
            $table->tinyInteger('rb_is_pemasukan')->default(1)->after('rb_remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rb_hist', function (Blueprint $table) {
            $table->dropColumn('rb_is_pemasukan');
        });
    }
}
