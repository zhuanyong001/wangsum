<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaderRebateAndReferralFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('leader_rebate_discount', 8, 4)->default(1)->comment('领导者返佣折扣率');
            $table->decimal('leader_referral_fee_rate', 8, 4)->default(0)->comment('领导者推荐费率');
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
            $table->dropColumn(['leader_rebate_discount', 'leader_referral_fee_rate']);
        });
    }
}
