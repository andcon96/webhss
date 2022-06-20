<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rute', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rute_tipe_id')->index();
            $table->foreign('rute_tipe_id')->references('id')->on('tipetruck');
            $table->unsignedBigInteger('rute_shipfrom_id')->index();
            $table->foreign('rute_shipfrom_id')->references('id')->on('shipfrom');
            $table->unsignedBigInteger('rute_customership_id')->index();
            $table->foreign('rute_customership_id')->references('id')->on('customership');
            $table->decimal('rute_harga',15,2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rute');
    }
}
