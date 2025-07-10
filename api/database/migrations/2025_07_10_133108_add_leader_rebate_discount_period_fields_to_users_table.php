<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaderRebateDiscountPeriodFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('leader_rebate_discount_90', 8, 4)->default(1)->comment('领导者返佣90天折扣率');
            $table->decimal('leader_rebate_discount_180', 8, 4)->default(1)->comment('领导者返佣180天折扣率');
            $table->decimal('leader_rebate_discount_365', 8, 4)->default(1)->comment('领导者返佣365天折扣率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['leader_rebate_discount_90', 'leader_rebate_discount_180', 'leader_rebate_discount_365']);
        });
    }
}
