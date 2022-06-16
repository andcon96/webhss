<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrefixToDomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domain', function (Blueprint $table) {
            $table->string('domain_so_prefix',2)->default('SO');
            $table->string('domain_so_rn',8)->default('000000');
            $table->string('domain_kr_prefix',2)->default('KR');
            $table->string('domain_kr_rn',8)->default('000000');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domain', function (Blueprint $table) {
            $table->dropColumn('domain_so_prefix');
            $table->dropColumn('domain_so_rn');
            $table->dropColumn('domain_kr_prefix');
            $table->dropColumn('domain_kr_rn');
        });
    }
}
