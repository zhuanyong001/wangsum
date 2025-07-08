<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaderRatesToMiningPoolCycleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pool_cycle_items', function (Blueprint $table) {
            $table->decimal('leader_rebate_rate', 5, 3)->default(0)->comment('领导奖励率');
            $table->decimal('leader_referral_fee_rate', 5, 3)->default(0)->comment('领导推荐费用率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mining_pool_cycle_items', function (Blueprint $table) {
            $table->dropColumn(['leader_rebate_rate', 'leader_referral_fee_rate']);
        });
    }
}
