<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPoolOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_pool_orders', function (Blueprint $table) {
            $table->id();
            //矿池id
            $table->integer('mining_pool_id')->comment('矿池id');
            //订单号
            $table->string('order_no')->comment('订单号');
            //用户id
            $table->integer('user_id')->comment('用户id');
            //币种code
            $table->string('coin_code')->comment('币种');
            //币种id
            $table->integer('currency_id')->comment('币种id');
            //数量
            $table->decimal('amount', 38, 8)->comment('数量');
            //日利率  
            $table->decimal('daily_rate', 18, 8)->comment('日化收益');
            //结算基值
            $table->decimal('settlement_base', 38, 8)->default(0)->unsigned()->comment('结算基值');
            //累计收益
            $table->decimal('total_award', 38, 8)->default(0)->comment('累计收益');
            //周期
            $table->integer('cycle')->comment('周期');
            //状态
            $table->tinyInteger('status')->comment('状态');
            //类型 1:活期 2:定期
            $table->tinyInteger('type')->comment('类型');
            //复利 0:否 1:是
            $table->tinyInteger('compound')->default(0)->comment('复利:0:否 1:是');
            //到期时间
            $table->timestamp('expire_time')->nullable()->comment('到期时间');
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
        Schema::dropIfExists('mining_pool_orders');
    }
}
