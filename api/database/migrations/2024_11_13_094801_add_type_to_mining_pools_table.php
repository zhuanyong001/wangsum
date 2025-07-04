<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToMiningPoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pools', function (Blueprint $table) {
            $table->integer('cate')->default(1)->comment('1 矿池类型, 2 存款类型');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mining_pools', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
