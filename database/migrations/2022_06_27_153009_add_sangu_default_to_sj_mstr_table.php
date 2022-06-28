<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSanguDefaultToSjMstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sj_mstr', function (Blueprint $table) {
            $table->decimal('sj_default_sangu', 20, 2)
                  ->default(0.00)
                  ->after('sj_tot_sangu');

            $table->unsignedBigInteger('sj_default_sangu_type')
                  ->unsigned()
                  ->nullable()
                  ->after('sj_default_sangu');

            $table->foreign('sj_default_sangu_type')
                  ->references('id')
                  ->on('rute_history');
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
            $table->dropForeign(['sj_default_sangu_type']);
            $table->dropColumn('sj_default_sangu_type');
            $table->dropColumn('sj_default_sangu');
        });

    }
}
