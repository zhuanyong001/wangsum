<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPoolOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_pool_orders', function (Blueprint $table) {
            $table->id();
            //借款池 id
            $table->unsignedBigInteger('loan_pool_id')->comment('借款池 id');
            //订单号
            $table->string('order_no', 50)->unique()->comment('订单号');
            //用户id
            $table->unsignedBigInteger('user_id')->comment('用户id');
            //贷币种id
            $table->unsignedBigInteger('loan_coin_id')->comment('贷币种id');
            //贷币种数量
            $table->decimal('loan_amount', 38, 8)->comment('贷币种数量');
            //抵押币种id
            $table->unsignedBigInteger('pledge_coin_id')->comment('抵押币种id');
            //抵押币种数量
            $table->decimal('pledge_amount', 20, 8)->comment('抵押币种数量');
            //贷款利率
            $table->decimal('loan_rate', 10, 2)->comment('贷款利率');
            //质押贷款 比例
            $table->decimal('loan_ratio', 10, 2)->comment('质押贷款 比例');
            //订单状态
            $table->tinyInteger('status')->default(1)->comment('订单状态 1:未完成 2:已完成');
            //利息
            $table->decimal('interest', 20, 8)->default(0)->comment('利息');
            //计息次数
            $table->integer('interest_times')->default(0)->comment('计息次数');
            //还款时间
            $table->timestamp('repayment_time')->nullable()->comment('还款时间');
            //最后计息时间
            $table->timestamp('last_interest_time')->nullable()->comment('最后计息时间');
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
        Schema::dropIfExists('loan_pool_orders');
    }
}
