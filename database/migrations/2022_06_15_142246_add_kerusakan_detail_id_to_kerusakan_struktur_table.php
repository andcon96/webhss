<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKerusakanDetailIdToKerusakanStrukturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kerusakan_struktur', function (Blueprint $table) {
            $table->unsignedBigInteger('kerusakan_struktur_detail_id')->index();
            $table->foreign('kerusakan_struktur_detail_id')->references('id')->on('kerusakan_detail')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kerusakan_struktur', function (Blueprint $table) {
            $table->dropForeign('kerusakan_struktur_kerusakan_struktur_detail_id_index');
            $table->dropColumn('kerusakan_struktur_detail_id');
        });
    }
}
