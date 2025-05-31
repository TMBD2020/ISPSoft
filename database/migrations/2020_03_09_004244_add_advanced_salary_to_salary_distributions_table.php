<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvancedSalaryToSalaryDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_distributions', function (Blueprint $table) {
            //
            $table->decimal('advanced_salary',11,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_distributions', function (Blueprint $table) {
            //
            $table->dropColumn(['advanced_salary']);
        });
    }
}
