<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_api', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('api_name')->nullable();
            $table->text('api_url');
            $table->text('api_sender');
            $table->string('api_username',200);
            $table->string('api_password',500);
            $table->double('sms_rate',11,2)->nullable()->default(0);
            $table->integer('api_default')->nullable()->default(0);
            $table->tinyInteger('api_status')->nullable()->default(1);
            $table->integer('branch_id')->nullable();
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
        Schema::dropIfExists('sms_api');
    }
}
