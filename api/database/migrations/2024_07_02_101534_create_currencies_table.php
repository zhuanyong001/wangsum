<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('币种名称');
            $table->string('code')->unique()->comment('币种代码');
            //最新价格
            $table->decimal('price', 20, 8)->default(0)->comment('最新价格');
            //获取价格的连接地址
            $table->string('price_url')->nullable()->comment('获取价格的连接地址');
            //24小时涨跌幅
            $table->decimal('change_24h', 20, 8)->default(0)->comment('24小时涨跌幅');
            $table->string('contract_address')->nullable()->comment('合约地址');
            $table->string('icon')->nullable()->comment('图标');
            $table->tinyInteger('status')->default(1)->comment('状态 1:启用 0:禁用');
            $table->integer('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('currencies');
    }
}
