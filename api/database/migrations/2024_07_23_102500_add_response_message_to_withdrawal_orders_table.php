<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponseMessageToWithdrawalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawal_orders', function (Blueprint $table) {
            //传教一个text类型的字段
            $table->text('response_message')->nullable()->comment('响应消息');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdrawal_orders', function (Blueprint $table) {
            //
        });
    }
}
