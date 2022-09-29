<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrefixInvoice2ToPrefixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prefix', function (Blueprint $table) {
            $table->string('prefix_iv')->after('prefix_kr_rn');
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
            $table->dropColumn('prefix_iv');
        });
    }
}
