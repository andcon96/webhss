<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_mstr', function (Blueprint $table) {
            $table->id();
            // $table->string('co_domain');
            $table->string('co_nbr',8);
            $table->string('co_cust_code');
            $table->string('co_type');
            $table->enum('co_status',['New','Ongoing','Closed','Cancelled']);
            $table->text('co_remark')->nullable();
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
        Schema::dropIfExists('co_mstr');
    }
}
