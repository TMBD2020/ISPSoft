<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemecolorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themecolors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('user_id')->nullable();
            $table->string('header_bg_color_1')->nullable();
            $table->string('header_bg_color_2')->nullable();
            $table->string('sidebar_bg_color')->nullable();
            $table->string('sidebar_text_color')->nullable();
            $table->string('body_bg_color')->nullable();
            $table->string('card_bg_color')->nullable();
            $table->string('button_bg_color')->nullable();
            $table->string('button_text_color')->nullable();
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
        Schema::dropIfExists('themecolors');
    }
}
