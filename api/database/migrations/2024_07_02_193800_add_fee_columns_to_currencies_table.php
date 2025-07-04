<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeColumnsToCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            // 添加固定手续费字段
            $table->decimal('fixed_fee', 38, 8)->default(0)->comment('固定手续费');
            // 添加比例手续费字段
            $table->decimal('percentage_fee', 38, 8)->default(0)->comment('比例手续费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            //
            $table->dropColumn('fixed_fee');
            $table->dropColumn('percentage_fee');
        });
    }
}
