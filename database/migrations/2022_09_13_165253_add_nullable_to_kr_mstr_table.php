<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToKrMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kr_mstr', function (Blueprint $table) {
            $table->unsignedBigInteger('kr_truck')->nullable(true)->change();
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
        Schema::table('kr_mstr', function (Blueprint $table) {
            $table->unsignedBigInteger('kr_truck')->nullable(false)->change();
            //
        });
    }
}
