<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewtrucknoteToTruck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('truck', function (Blueprint $table) {
            $table->text('new_truck_note')->nullable()->after('truck_is_active');
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
        Schema::table('truck', function (Blueprint $table) {
            $table->dropColumn('new_truck_note');
        });
    }
}
