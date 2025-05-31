<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiftChargeToLineShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line_shifts', function (Blueprint $table) {
            $table->decimal('shift_charge',11,2)->default(0)->nullable();
            $table->string('ref_ticket_no',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_shifts', function (Blueprint $table) {
            $table->dropColumn(['shift_charge','ref_ticket_no']);
        });
    }
}
