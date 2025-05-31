<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueInstallmentEmpAdvancedSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_advanced_salary', function (Blueprint $table) {
            //
            $table->tinyInteger('due_installment')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_advanced_salary', function (Blueprint $table) {
            //
            $table->dropColumn(['due_installment']);
        });
    }
}
