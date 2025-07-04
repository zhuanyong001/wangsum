<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title_lang_key')->nullable()->comment('公告标题对应的多语言key');
            $table->string('content_lang_key')->nullable()->comment('公告内容对应的多语言key');
            $table->tinyInteger('type')->default(0)->comment('公告类型 0:普通公告');
            $table->tinyInteger('status')->default(0)->comment('公告状态 0:未发布 1:已发布');
            $table->integer('sort')->default(0)->comment('公告排序');
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
        Schema::dropIfExists('notices');
    }
}
