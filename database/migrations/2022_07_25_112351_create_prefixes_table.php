<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefix', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_co',2)->default('CO');
            $table->string('prefix_co_rn',8)->default('000000');
            $table->string('prefix_so',2)->default('SO');
            $table->string('prefix_so_rn',8)->default('000000');
            $table->string('prefix_sj',2)->default('SJ');
            $table->string('prefix_sj_rn',8)->default('000000');
            $table->string('prefix_kr',2)->default('KR');
            $table->string('prefix_kr_rn',8)->default('000000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prefix');
    }
}
