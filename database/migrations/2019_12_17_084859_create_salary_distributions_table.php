<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_distributions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('ref_emp_id');
            $table->integer('ref_branch_id')->nullable();
            $table->integer('salary_year');
            $table->integer('salary_month');
            $table->text('emp_salary');
            $table->integer('emp_late_days')->default(0);
            $table->integer('emp_absent_days')->default(0);
            $table->decimal('emp_absent_fine',11,2);
            $table->decimal('emp_others_fine',11,2);
            $table->decimal('advance_deduction',11,2);
            $table->integer('created_by');
            $table->integer('approved_by')->default(0);
            $table->tinyInteger('is_approved')->default(0);
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
        Schema::dropIfExists('salary_distributions');
    }
}
