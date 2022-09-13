<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNewenumToKerusakanMstr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kr_mstr', function (Blueprint $table) {
            
            DB::statement("ALTER TABLE kr_mstr MODIFY COLUMN kr_status ENUM('New','Ongoing','WIP','Done','Cancelled','Need Approval','Reject','Close')");
            $table->string('kr_gandeng')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kr_mstr', function (Blueprint $table) {
            DB::statement("ALTER TABLE kr_mstr MODIFY COLUMN kr_status ENUM('New','Ongoing','Done','Cancelled','Need Approval','Reject','Close')");
            $table->dropColumn('kr_gandeng');
        });
    }
}
