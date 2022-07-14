<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_mstr', function (Blueprint $table) {
            $table->id();
            $table->string('so_domain',8);
            $table->unsignedBigInteger('so_co_mstr_id')->index();
            $table->foreign('so_co_mstr_id')->references('id')->on('co_mstr');
            $table->string('so_nbr',8);
            $table->string('so_ship_from',8)->nullable();
            $table->string('so_ship_to',8);
            $table->date('so_due_date');
            $table->date('so_effdate')->nullable();
            // $table->enum('so_type',['Berat','Rits','Shift']);
            $table->enum('so_status',['Open','Closed','Cancelled','Selesai'])->default('Open');
            $table->text('so_remark')->nullable();
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
        Schema::dropIfExists('so_mstr');
    }
}
