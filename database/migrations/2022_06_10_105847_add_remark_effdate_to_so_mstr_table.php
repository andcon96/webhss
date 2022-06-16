<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarkEffdateToSoMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('so_mstr', function (Blueprint $table) {
            $table->string('so_remark')->nullable();
            $table->date('so_effdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('so_mstr', function (Blueprint $table) {
            $table->dropColumn('so_remark');
            $table->dropColumn('so_effdate');
        });
    }
}
