<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirDropLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_drop_logs', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique()->comment('空投订单号');
            $table->bigInteger('user_id')->comment('用户ID');
            $table->decimal('amount', 20, 8)->comment('空投数量');
            $table->bigInteger('currency_id')->comment('币种ID');
            $table->bigInteger('air_drop_order_id')->comment('空投订单ID');
            $table->bigInteger('mining_pool_order_id')->comment('矿池订单ID');
            $table->tinyInteger('status')->default(0)->comment('状态 0:待发放 1:已发放');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('air_drop_logs');
    }
}
