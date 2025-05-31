<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpcomingClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upcoming_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('ref_zone_id');
            $table->string('client_name');
            $table->text('client_address')->nullable();
            $table->string('client_mobile');
            $table->integer('ref_package_id');
            $table->decimal('otc')->nullable()->default(0);
            $table->date('setup_date')->nullable();
            $table->string('previous_isp')->nullable();
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
        Schema::dropIfExists('upcoming_clients');
    }
}
