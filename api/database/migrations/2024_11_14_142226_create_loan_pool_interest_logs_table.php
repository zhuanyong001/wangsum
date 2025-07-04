<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPoolInterestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_pool_interest_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_pool_order_id')->comment('借款订单id');
            $table->decimal('interest_amount', 20, 8)->comment('利息');
            //唯一交易号 用于防止重复发放奖励  交易号生成规则：矿池订单id_用户id_时间Ymd
            $table->string('trade_no', 50)->unique()->comment('唯一交易号');
            //user_id
            $table->unsignedBigInteger('user_id')->comment('用户id');
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
        Schema::dropIfExists('loan_pool_interest_logs');
    }
}
