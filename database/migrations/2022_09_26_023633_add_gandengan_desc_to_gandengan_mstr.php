<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGandenganDescToGandenganMstr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gandengan_mstr', function (Blueprint $table) {
            $table->string('gandeng_desc',255)->nullable()->after('gandeng_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gandengan_mstr', function (Blueprint $table) {
            $table->dropColumn('gandeng_desc');

        });
    }
}
