<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDecimalToRuteHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rute_history', function (Blueprint $table) {
            $table->decimal('history_ongkos','20','3')->nullable()->change();
            $table->decimal('history_sangu','20','3')->nullable()->change();
            $table->decimal('history_harga','20','3')->nullable()->change();
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
            $table->decimal('history_ongkos','20','2')->nullable()->change();
            $table->decimal('history_sangu','20','2')->nullable()->change();
            $table->decimal('history_harga','20','2')->nullable()->change();
        });
    }
}
