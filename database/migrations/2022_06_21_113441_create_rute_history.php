<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuteHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rute_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_rute_id')->index();
            $table->foreign('history_rute_id')->references('id')->on('rute')->onDelete('restrict');
            $table->decimal('history_harga',15,2);
            $table->decimal('history_ongkos',15,2);
            $table->decimal('history_sangu',15,2);
            $table->tinyInteger('history_is_active')->default(1);
            $table->dateTime('history_last_active')->nullable();
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
        Schema::dropIfExists('rute_history');
    }
}
