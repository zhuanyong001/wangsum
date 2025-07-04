<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_orders', function (Blueprint $table) {
            // 主键，自动增长
            $table->id();
            //唯一订单号
            $table->string('order_no', 255)->unique()->comment('唯一订单号');
            // 用户ID，关联用户表
            $table->unsignedBigInteger('user_id')->comment('用户ID，关联用户表');
            // 提现的数字币类型
            $table->string('currency', 10)->comment('提现的数字币类型');
            //币种id
            $table->unsignedBigInteger('currency_id')->comment('币种id');
            // 提现金额
            $table->decimal('amount', 38, 8)->comment('提现金额');
            // 提现手续费
            $table->decimal('fee', 38, 8)->default(0)->comment('提现手续费');
            // 提现目标地址
            $table->string('destination_address', 255)->comment('提现目标地址');
            // 订单状态，1:待审核，2:处理中，3:完成，-1:失败
            $table->tinyInteger('status')->default(1)->comment('订单状态，1:待审核，2:处理中，3:完成，-1:失败');
            // 订单创建时间，默认当前时间戳
            $table->timestamp('created_at')->useCurrent()->comment('订单创建时间');
            // 订单最后更新时间，每次更新时自动设置为当前时间戳
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('订单更新时间');
            // 订单处理时间，可为空
            $table->timestamp('processed_at')->nullable()->comment('订单处理时间');
            // 区块链交易ID，用于记录区块链上的交易ID
            $table->string('transaction_id', 255)->nullable()->comment('区块链交易ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawal_orders');
    }
}
