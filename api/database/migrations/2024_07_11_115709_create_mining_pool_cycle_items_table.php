<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPoolCycleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_pool_cycle_items', function (Blueprint $table) {
            $table->id();
            //名称
            $table->string('name', 255)->comment('名称');
            $table->integer('days')->comment('质押周期的天数');
            $table->tinyInteger('type')->comment('类型：1活期，2定期');
            $table->decimal('daily_rate', 5, 4)->comment('日利率');
            $table->tinyInteger('compound')->default(0)->comment('是否复利 0:否 1:是');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mining_pool_cycle_items');
    }
}
