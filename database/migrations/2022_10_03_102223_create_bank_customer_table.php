<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_customer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bc_customer_id')->index();
            $table->foreign('bc_customer_id')->references('id')->on('customer');
            $table->unsignedBigInteger('bc_domain_id')->index();
            $table->foreign('bc_domain_id')->references('id')->on('domain');
            $table->string('bc_acc_name',50);
            $table->string('bc_acc_nbr',50);
            $table->tinyInteger('bc_is_active')->default('1');
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
        Schema::dropIfExists('bank_customer');
    }
}
