<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpAdvancedSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_advanced_salary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('ref_emp_id');
            $table->decimal('advance_amount',11,2)->default(0);
            $table->decimal('payment_amount',11,2)->default(0);
            $table->integer('installment_time')->default(1);
            $table->date('receive_date');
            $table->integer('receive_from')->nullable();
            $table->integer('is_installment')->nullable()->default(0);
            $table->integer('ref_branch_id')->nullable()->default(1);
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
        Schema::dropIfExists('emp_advanced_salary');
    }
}
