<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubDomainToTruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('truck', function (Blueprint $table) {
            $table->string('truck_sub_domain')->nullable()->after('truck_domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('truck', function (Blueprint $table) {
            $table->dropColumn('truck_sub_domain');
        });
    }
}
