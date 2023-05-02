<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSjCustToSjMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sj_mstr', function (Blueprint $table) {
            $table->string('sj_surat_jalan_customer',30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sj_mstr', function (Blueprint $table) {
            $table->dropColumn('sj_surat_jalan_customer');
        });
    }
}
