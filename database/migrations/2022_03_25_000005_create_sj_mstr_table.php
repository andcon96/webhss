<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSjMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sj_mstr', function (Blueprint $table) {
            $table->id();
            $table->string('sj_domain');
            $table->unsignedBigInteger('sj_so_mstr_id')->index();
            $table->foreign('sj_so_mstr_id')->references('id')->on('so_mstr');
            $table->string('sj_nbr');
            $table->date('sj_eff_date');
            $table->text('sj_remark')->nullable();
            $table->enum('sj_status',['Open','Selesai','Closed','Cancelled']);
            $table->unsignedBigInteger('sj_truck_id')->index();
            $table->foreign('sj_truck_id')->references('id')->on('truck');
            $table->integer('sj_jmlh_trip');
            $table->decimal('sj_tot_sangu',20,2);
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
        Schema::dropIfExists('sj_mstr');
    }
}
