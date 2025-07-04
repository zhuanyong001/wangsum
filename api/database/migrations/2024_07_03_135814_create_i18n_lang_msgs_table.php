<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateI18nLangMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('i18n_lang_msgs', function (Blueprint $table) {
            $table->id();
            $table->integer('keyID')->nullable();
            $table->integer('langID')->nullable();
            $table->string('msg', 2048)->nullable();
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
        Schema::dropIfExists('i18n_lang_msgs');
    }
}
