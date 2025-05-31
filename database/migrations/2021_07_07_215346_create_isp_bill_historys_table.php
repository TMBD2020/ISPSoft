<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIspBillHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isp_bill_historys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('bill_id');
            $table->bigInteger('client_id');
            $table->string('particular')->nullable();
            $table->double('bill_amount',20,2)->default(0);
            $table->double('receive_amount',20,2)->default(0);
            $table->integer('bill_month')->default(0)->nullable();
            $table->integer('bill_year')->default(0)->nullable();
            $table->tinyInteger('bill_status')->default(0)->nullable();
            $table->tinyInteger('bill_type')->default(1)->nullable()->comment('1=monthly,2=custom');
            $table->tinyInteger('client_type')->default(1)->nullable()->comment('1=isp_c,2=catv_c,3=isp_r,4=catv_r');
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
        Schema::dropIfExists('isp_bill_historys');
    }
}
