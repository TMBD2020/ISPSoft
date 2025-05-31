<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LiabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('person_id');
            $table->string('receiver');
            $table->integer('receive_from');
            $table->decimal('receive_amount', 11, 2)->default(0)->nullable();
            $table->decimal('payment_amount', 11, 2)->default(0)->nullable();
            $table->tinyInteger('loan_type')->default(1)->nullable();//1=loan receive, 2=loan payment
            $table->decimal('current_due',11,2)->nullable()->default(0);
            $table->date('receive_date');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('liabilities');
    }
}
