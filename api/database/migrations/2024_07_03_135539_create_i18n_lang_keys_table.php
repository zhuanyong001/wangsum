<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateI18nLangKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('i18n_lang_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable();
            $table->string('page')->nullable();
            $table->integer('is_front')->default(0);
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
        Schema::dropIfExists('i18n_lang_keys');
    }
}
