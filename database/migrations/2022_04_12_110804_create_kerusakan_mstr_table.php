<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerusakanMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kerusakan_mstr', function (Blueprint $table) {
            $table->id();
            $table->string('kerusakan_domain',8);
            $table->string('kerusakan_nbr',15);
            $table->unsignedBigInteger('kerusakan_truck_driver')->index();
            $table->foreign('kerusakan_truck_driver')->references('id')->on('truckdriver')->onDelete('restrict');
            $table->date('kerusakan_date');
            $table->enum('kerusakan_status',['New','Ongoing','Done','Cancelled']);
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
        Schema::dropIfExists('kerusakan_mstr');
    }
}
