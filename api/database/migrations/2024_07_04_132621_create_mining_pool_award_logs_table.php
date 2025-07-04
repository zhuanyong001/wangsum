<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPoolAwardLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_pool_award_logs', function (Blueprint $table) {
            $table->id();
            //对应的矿池订单
            $table->integer('mining_pool_order_id')->index();
            //对应的用户
            $table->integer('user_id')->index();
            //奖励金额
            $table->decimal('amount', 38, 8);
            //唯一交易号 用于防止重复发放奖励  交易号生成规则：矿池订单id_用户id_时间Ymd
            $table->string('trade_no', 50)->unique();
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
        Schema::dropIfExists('mining_pool_award_logs');
    }
}
