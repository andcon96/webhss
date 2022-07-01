<?php
//=========================
//created at 14-12-2021
//=========================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unsignedBigInteger('dept_id')->index();
            $table->foreign('dept_id')->references('id')->on('departments');
            $table->unsignedBigInteger('role_type_id')->index();
            $table->foreign('role_type_id')->references('id')->on('role_types');
            $table->tinyInteger('isActive');
            $table->string('domain');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->text('session_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}
