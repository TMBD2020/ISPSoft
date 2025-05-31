<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefExpenseIdEmpAdvancedSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_advanced_salary', function (Blueprint $table) {
            $table->integer('ref_expense_id');
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
            $table->dropColumn(['ref_expense_id']);
            //
        });
    }
}
