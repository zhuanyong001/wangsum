<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAssetLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_asset_logs', function (Blueprint $table) {
            $table->id();
            //userasset_id
            $table->foreignId('user_asset_id')->constrained()->onDelete('cascade');
            //user_id
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //变动金额
            $table->decimal('amount', 38, 8)->comment('变动金额');
            //变动类型
            $table->tinyInteger('type')->comment('变动类型 1:充值 2:提现 3:兑换 4:奖励');
            //描述
            $table->string('description')->nullable()->comment('描述');
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
        Schema::dropIfExists('user_asset_logs');
    }
}
