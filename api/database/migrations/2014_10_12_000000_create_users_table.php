<?php

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
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('share_code', 55)->nullable()->comment('分享码');
            $table->string('avatar')->nullable()->comment('头像');
            $table->unsignedBigInteger('referrer_id')->comment('推荐人ID')->nullable(); // 推荐人ID
            $table->string('tron_address')->unique()->nullable();  // 确保地址唯一
            $table->decimal('balance', 10, 2)->default(0); // 假设余额是一个十进制数，最多有10位数字，其中2位是小数

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
