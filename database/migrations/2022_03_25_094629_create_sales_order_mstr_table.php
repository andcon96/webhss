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
            $table->string('so_nbr',8);
            $table->string('so_cust',8);
            $table->enum('so_type',['Berat','Rits','Shift']);
            $table->string('so_ship_from',8);
            $table->string('so_ship_to',8);
            $table->date('so_due_date');
            $table->string('so_domain',8);
            $table->enum('so_status',['New','Open','Closed','Cancelled','Selesai']);
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
