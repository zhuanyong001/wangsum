<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrialAmountToMiningPoolOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pool_orders', function (Blueprint $table) {
            $table->decimal('trial_amount', 20, 8)->default(0)->comment('体验金金额');
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
            $table->dropColumn('trial_amount');
        });
    }
}
