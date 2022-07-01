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
        Schema::create('kr_mstr', function (Blueprint $table) {
            $table->id();
            $table->string('kr_domain',8);
            $table->string('kr_nbr',15);
            $table->unsignedBigInteger('kr_truck')->index();
            $table->foreign('kr_truck')->references('id')->on('truck');
            $table->date('kr_date');
            $table->enum('kr_status',['New','Ongoing','Done','Cancelled','Need Approval','Reject']);
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
