<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToLiabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liabilities', function (Blueprint $table) {
            $table->decimal('installment_per_month',11,2)->nullable()->default(0);
            $table->integer('installment_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liabilities', function (Blueprint $table) {
            $table->dropColumn(['installment_per_month','installment_date']);
        });
    }
}
