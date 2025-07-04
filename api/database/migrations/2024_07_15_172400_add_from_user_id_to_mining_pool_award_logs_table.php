<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromUserIdToMiningPoolAwardLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pool_award_logs', function (Blueprint $table) {
            // 假设from_user_id是一个整数类型
            $table->unsignedBigInteger('from_user_id')->after('id')->default(0); // 如果你希望这个字段在'id'字段之后
            //类型  1：订单奖励   2：直属下级返利  3：间接下级返利  
            $table->tinyInteger('type')->default(1)->after('amount')->comment('类型  1：订单奖励   2：直属下级返利  3：间接下级返利');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('mining_pool_award_logs', function (Blueprint $table) {
            $table->dropColumn('from_user_id');
            $table->dropColumn('type');
        });
    }
}
