<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('ticket_no')->unique();
            $table->integer('ref_client_id');
            $table->integer('ref_department_id');
            $table->string('subject');
            $table->text('complain');			
            $table->integer('opened_by')->nullable();
            $table->integer('closed_by')->nullable();
            $table->timestamp('ticket_datetime')->nullable();
            $table->timestamp('close_datetime')->nullable();
            $table->tinyInteger('ticket_status')->nullable()->default(1);
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
        Schema::dropIfExists('tickets');
    }
}
