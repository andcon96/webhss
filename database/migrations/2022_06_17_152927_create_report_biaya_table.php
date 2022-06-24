<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rb_hist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rb_truck_id')->index();
            $table->foreign('rb_truck_id')->references('id')->on('truck')->onDelete('restrict');
            $table->date('rb_eff_date');
            $table->decimal('rb_nominal',20,2);
            $table->text('rb_remark')->nullable();
            $table->tinyInteger('rb_is_active')->default('1');
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
        Schema::dropIfExists('report_biaya');
    }
}
