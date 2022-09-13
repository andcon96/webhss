<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCicilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cicilan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cicilan_dn_id')->index();
            $table->foreign('cicilan_dn_id')->references('id')->on('driver_nopol');
            $table->string('cicilan_remarks',255)->nullable();
            $table->date('cicilan_eff_date');
            $table->decimal('cicilan_nominal',15,2);
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
        Schema::dropIfExists('cicilan');
    }
}
