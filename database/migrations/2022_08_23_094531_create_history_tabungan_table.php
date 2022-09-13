<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTabunganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_tabungan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ht_tabungan_id')->index();
            $table->foreign('ht_tabungan_id')->references('id')->on('tabungan');
            $table->string('ht_remarks',255)->nullable();
            $table->date('ht_eff_date');
            $table->decimal('ht_nominal',15,2);
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
        Schema::dropIfExists('history_tabungan');
    }
}
