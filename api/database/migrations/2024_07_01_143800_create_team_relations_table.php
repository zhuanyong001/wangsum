<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_relations', function (Blueprint $table) {
            $table->id(); // 自增ID作为主键
            $table->unsignedBigInteger('inviter_id'); // 邀请人ID
            $table->unsignedBigInteger('invitee_id'); // 下级ID
            $table->integer('level'); // 层级关系
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
        Schema::dropIfExists('team_relations');
    }
}
