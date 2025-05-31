<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('sms_receiver');
            $table->string('sms_sender');
            $table->integer('sms_count')->default(1);
            $table->string('sms_type');
            $table->text('sms_text');
            $table->enum('sms_status',["Pending","Sent","Receiver Error","Failed"])->default("Pending");
            $table->smallInteger('is_retry')->default(0)->nullable();
            $table->dateTime('sent_time')->nullable();
            $table->string('sms_api',1000);
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
        Schema::dropIfExists('sms_history');
    }
}
