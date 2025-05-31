<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('bill_id')->unique();
            $table->date('bill_date');
            $table->date('receive_date')->nullable();
            $table->integer('receive_by')->nullable();
            $table->integer('bill_month');
            $table->integer('bill_year');
            $table->integer('reseller_id');
            $table->integer('package_id');
            $table->string('reseller_initial_id');
            $table->integer('bill_type');
            $table->integer('reseller_type')->default(0);
            $table->integer('bill_status')->default(0);
            $table->float('payable_amount',11,2)->default(0)->nullable();
            $table->float('receive_amount',11,2)->default(0);
            $table->float('discount_amount',11,2)->default(0)->nullable();
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
        Schema::dropIfExists('reseller_bills');
    }
}
