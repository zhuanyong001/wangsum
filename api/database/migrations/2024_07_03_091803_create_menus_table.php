<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('badge')->nullable();
            $table->string('target')->nullable();
            $table->string('path');
            $table->string('component');
            $table->boolean('renderMenu');
            $table->string('parent')->nullable();
            $table->string('permission')->nullable();
            $table->boolean('cacheable');
            $table->integer('sort')->default(0);
            $table->string('link')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
