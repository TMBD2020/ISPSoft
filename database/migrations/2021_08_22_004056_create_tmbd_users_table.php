<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmbdUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmbd_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reg_no')->unique();
            $table->string('name');
            $table->string('mobile');
            $table->string('alt_mobile');
            $table->string('email_id')->unique();
            $table->text('address');
            $table->string('logo')->nullable();
            $table->tinyInteger('email_verify')->default(0);
            $table->tinyInteger('approval')->default(0);
            $table->date('approve_date')->nullable();
            $table->tinyInteger('login_status')->nullable()->default(1);
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
        Schema::dropIfExists('tmbd_users');
    }
}
