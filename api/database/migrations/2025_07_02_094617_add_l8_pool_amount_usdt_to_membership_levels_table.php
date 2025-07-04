<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddL8PoolAmountUsdtToMembershipLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_levels', function (Blueprint $table) {
            $table->decimal('l8_pool_amount_usdt', 12, 2)->default(0)->comment('下8代矿池金额USDT要求');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_levels', function (Blueprint $table) {
            $table->dropColumn('l8_pool_amount_usdt');
        });
    }
}
