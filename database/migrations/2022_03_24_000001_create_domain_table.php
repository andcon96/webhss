<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain', function (Blueprint $table) {
            $table->id();
            $table->string('domain_code',8);
            $table->string('domain_desc',50);
            $table->string('domain_co_prefix',2)->default('CO');
            $table->string('domain_co_rn',8)->default('000000');
            $table->string('domain_so_prefix',2)->default('SO');
            $table->string('domain_so_rn',8)->default('000000');
            $table->string('domain_sj_prefix',2)->default('SJ');
            $table->string('domain_sj_rn',8)->default('000000');
            $table->string('domain_kr_prefix',2)->default('KR');
            $table->string('domain_kr_rn',8)->default('000000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domain');
    }
}
