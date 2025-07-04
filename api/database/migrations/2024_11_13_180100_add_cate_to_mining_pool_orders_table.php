<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCateToMiningPoolOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pool_orders', function (Blueprint $table) {
            //
            $table->integer('cate')->default(1)->comment('分类1:矿池 2:存款池');
            $table->unsignedBigInteger('df_currency_id')->default(0)->comment('奖励币种id');
            $table->decimal('df_rate', 10, 4)->default(0)->comment('奖励利率');
            $table->decimal('df_amount', 38, 8)->default(0)->comment('奖励金额');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mining_pool_orders', function (Blueprint $table) {
            //
        });
    }
}
