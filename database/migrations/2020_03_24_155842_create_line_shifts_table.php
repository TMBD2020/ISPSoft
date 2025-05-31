<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('ref_client_id');
            $table->text('old_address');
            $table->integer('old_zone_id');
            $table->integer('old_node_id');
            $table->integer('old_box_id');
            $table->text('new_address');
            $table->integer('new_zone_id');
            $table->integer('new_node_id');
            $table->integer('new_box_id');
            $table->date('shift_date');
            $table->string('contact_no')->nullable();
            $table->text('note')->nullable();
            $table->integer('shift_status')->nullable()->default(0);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('line_shifts');
    }
}
