<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaseTotalAmountToMiningPoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mining_pools', function (Blueprint $table) {
            //
            $table->decimal('base_total_amount', 18, 8)->default(0)->comment('基础总量');
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
            //
        });
    }
}
