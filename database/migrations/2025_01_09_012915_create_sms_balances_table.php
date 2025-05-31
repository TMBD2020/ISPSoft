<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_balances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("company_id");
            $table->string("particular")->nullable();
            $table->bigInteger("sms_qty");
            $table->double("sms_rate",20,2)->default(0);
            $table->double("amount",20,2)->default(0);
            $table->string("sms_type")->nullable();
            $table->integer("transaction_type")->default(1)->comment("1=>cash")->nullable();
            $table->string("transaction_id")->nullable();
            $table->string("transaction_date");
            $table->string("note")->nullable();
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
        Schema::dropIfExists('sms_balances');
    }
};
