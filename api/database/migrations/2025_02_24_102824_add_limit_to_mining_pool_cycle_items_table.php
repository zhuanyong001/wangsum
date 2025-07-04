<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimitToMiningPoolCycleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pool_cycle_items', function (Blueprint $table) {
            $table->integer('limit')->default(0)->comment('限制数量')->after('df_rate');
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
            $table->dropColumn('limit');
        });
    }
}
