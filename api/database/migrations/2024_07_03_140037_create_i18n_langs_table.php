<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateI18nLangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('i18n_langs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->tinyInteger('status')->default(1)->comment('是否启用');
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
        Schema::dropIfExists('i18n_langs');
    }
}
