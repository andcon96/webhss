<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryCicilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_cicilan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hc_cicilan_id')->index();
            $table->foreign('hc_cicilan_id')->references('id')->on('cicilan');
            $table->string('hc_remarks',255)->nullable();
            $table->date('hc_eff_date');
            $table->decimal('hc_nominal',15,2);
            $table->tinyInteger('hc_is_active')->default(1);
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
        Schema::dropIfExists('history_cicilan');
    }
}
