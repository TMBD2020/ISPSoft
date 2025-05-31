<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('loan_to');
            $table->string('receive_from');
            $table->date('receive_date');
            $table->integer('person_id');
            $table->decimal('receive_amount', 11, 2)->default(0)->nullable();
            $table->decimal('payment_amount', 11, 2)->default(0)->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('loan_type')->default(1)->nullable();//1=loan receive, 2=loan payment
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
        Schema::dropIfExists('loans');
    }
}
