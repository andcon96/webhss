<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoryUserToRuteHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rute_history', function (Blueprint $table) {
            $table->unsignedBigInteger('history_user')->index()->default(1)->after('history_last_active');
            $table->foreign('history_user')->references('id')->on('users');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rute_history', function (Blueprint $table) {
            $table->dropForeign(['history_user']);
            $table->dropColumn('history_user');
        });
    }
}
