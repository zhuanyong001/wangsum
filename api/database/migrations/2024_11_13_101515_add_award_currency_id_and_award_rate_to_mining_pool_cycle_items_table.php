<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAwardCurrencyIdAndAwardRateToMiningPoolCycleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pool_cycle_items', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('df_currency_id')->default(0)->comment('奖励币种id');
            $table->decimal('df_rate', 10, 4)->default(0)->comment('奖励利率');
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
            //
            $table->dropColumn('df_currency_id');
            $table->dropColumn('df_rate');
        });
    }
}
