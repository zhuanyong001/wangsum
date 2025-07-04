<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('会员等级名称');
            $table->integer('level')->comment('会员等级，从1到4');
            $table->decimal('participation_commission', 8, 4)->comment('伞下参与金额提成');
            $table->decimal('equal_level_commission', 8, 4)->comment('平级推荐收益提成');
            $table->decimal('pool_amount_usdt', 12, 2)->nullable()->comment('矿池金额要求，单位USDT');
            $table->string('direct_lower_levels', 10)->nullable()->comment('直推下级要求');
            $table->integer('umbrella_people_count')->nullable()->comment('伞下人数要求');
            $table->text('remarks')->nullable()->comment('备注');
            $table->boolean('status')->default(1)->comment('状态，1表示开启，0表示关闭');
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
        Schema::dropIfExists('membership_levels');
    }
}
