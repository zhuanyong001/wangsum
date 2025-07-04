<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSceneToWithdrawalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawal_orders', function (Blueprint $table) {
            $table->string('scene')->nullable()->comment('支付场景')->after('status');
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
            $table->dropColumn('scene');
        });
    }
}
