<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefixTable extends Migration
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
            $table->string('prefix_so')->nullable();
            $table->string('rn_so')->nullable();
            $table->string('prefix_kerusakan')->nullable();
            $table->string('rn_kerusakan')->nullable();
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
