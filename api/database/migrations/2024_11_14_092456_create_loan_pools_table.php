<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_pools', function (Blueprint $table) {
            $table->id();
            //借款名称
            $table->string('name')->comment('矿池名称');
            //借款币种 
            $table->string('loan_coin_ids')->comment('借款币种');
            //质押币种
            $table->string('pledge_coin_ids')->comment('质押币种');
            //借款比例
            $table->decimal('loan_ratio', 5, 4)->comment('借款比例');
            //借款利率
            $table->decimal('loan_rate', 8, 4)->comment('日利率');
            //矿池状态
            $table->tinyInteger('status')->default(1)->comment('矿池状态 1:开启 2:关闭');
            //矿池排序
            $table->integer('sort')->default(0)->comment('矿池排序');

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
        Schema::dropIfExists('loan_pools');
    }
}
