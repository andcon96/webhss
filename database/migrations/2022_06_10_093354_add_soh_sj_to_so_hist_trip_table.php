<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSohSjToSoHistTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('so_hist_trip', function (Blueprint $table) {
            $table->string('soh_sj');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('so_hist_trip', function (Blueprint $table) {
            $table->dropColumn('soh_sj');
        });
    }
}
