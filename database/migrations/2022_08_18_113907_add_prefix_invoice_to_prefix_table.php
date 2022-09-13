<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrefixInvoiceToPrefixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prefix', function (Blueprint $table) {
            $table->string('prefix_iv_rn',20)->default(000000)->after('prefix_kr_rn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prefix', function (Blueprint $table) {
            $table->dropColumn('prefix_iv_rn');
        });
    }
}
