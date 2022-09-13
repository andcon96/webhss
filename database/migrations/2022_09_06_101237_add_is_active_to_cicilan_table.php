<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToCicilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cicilan', function (Blueprint $table) {
            $table->tinyInteger('cicilan_is_active')->default(1)->after('cicilan_nominal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cicilan', function (Blueprint $table) {
            $table->dropColumn('cicilan_is_active');
        });
    }
}
