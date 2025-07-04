<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleIdAndIsSuperToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('role_id')->default(0)->comment('角色id');
            $table->boolean('is_super')->default(0)->comment('是否超级管理员');
            //关联 前台用户
            $table->unsignedBigInteger('user_id')->default(0)->comment('关联前台用户');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
        });
    }
}
