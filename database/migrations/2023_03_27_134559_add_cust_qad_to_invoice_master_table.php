<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustQadToInvoiceMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_master', function (Blueprint $table) {
            $table->string('im_cust_code')->after('im_so_mstr_id')->default('')->nullable();
            $table->string('im_cust_qad')->after('im_cust_code')->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_master', function (Blueprint $table) {
            $table->dropColumn('im_cust_code');
            $table->dropColumn('im_cust_qad');
        });
    }
}
