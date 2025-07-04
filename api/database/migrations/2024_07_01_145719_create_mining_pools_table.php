<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_pools', function (Blueprint $table) {
            $table->id();
            //矿池名称
            $table->string('name')->comment('矿池名称');
            //质押币种
            $table->string('coin')->comment('质押币种');
            //质押周期
            $table->string('cycle')->comment('质押周期');
            //矿池状态
            $table->tinyInteger('status')->default(1)->comment('矿池状态 1:开启 2:关闭');
            //矿池描述
            $table->string('description')->nullable()->comment('矿池描述');
            //矿池排序
            $table->integer('sort')->default(0)->comment('矿池排序');
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
        Schema::dropIfExists('mining_pools');
    }
}
