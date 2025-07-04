<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirDropOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_drop_orders', function (Blueprint $table) {
            $table->id();
            //空投唯一标识
            $table->string('order_no')->comment('空投唯一标识');
            //最低参与金额（美金)
            $table->string('min_usd_amount')->comment('最低参与金额（美金)');
            //空投金额
            $table->string('amount_value')->comment('空投金额');
            //空投币种
            $table->bigInteger('currency_id')->comment('空投币种');
            //是否按比例
            $table->boolean('is_proportion')->default(0)->comment('是否按比例 0:否 1:是');
            //开始时间
            $table->timestamp('start_time')->comment('开始时间');
            //结束时间
            $table->timestamp('end_time')->comment('结束时间');
            //状态0：关闭 1：启动 默认0
            $table->tinyInteger('status')->default(0)->comment('状态 0:关闭 1:启动');
            //备注
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
        Schema::dropIfExists('air_drop_orders');
    }
}
