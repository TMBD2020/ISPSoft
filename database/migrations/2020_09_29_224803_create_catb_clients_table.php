<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatbClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catb_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('auth_id')->unique();
            $table->string('client_id');
            $table->string('client_name');
            $table->string('home_card_no')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('network_id');
            $table->integer('pop_id');
            $table->integer('zone_id');
            $table->integer('box_id');
            $table->integer('prefix_id')->nullable();
            $table->integer('payment_dateline');
            $table->integer('billing_date')->nullable();
            $table->string('cell_no');
            $table->integer('package_id');
            $table->integer('payment_id');
            $table->double('otc',11,2)->nullable()->default(0);
            $table->double('mrp',11,2)->nullable()->default(0);
            $table->integer('payment_alert_sms')->nullable()->default(0);
            $table->integer('payment_conformation_sms')->nullable()->default(0);
            $table->string('alter_cell_no_1')->nullable();
            $table->text('address')->nullable();
            $table->string('thana')->nullable();
            $table->date('join_date')->nullable();
            $table->string('occupation')->nullable();
            $table->string('email')->nullable();
            $table->string('nid')->nullable();
            $table->string('connection_mode')->nullable()->default(1);
            $table->tinyInteger('lock_status')->nullable()->default(0);
            $table->smallInteger('lock_sms')->nullable()->default(0);
            $table->timestamp('lock_datetime')->nullable();
            $table->timestamp('lock_commit_pay_deadline')->nullable();
            $table->integer('cable_id')->nullable();
            $table->string('required_cable')->nullable();
            $table->text('note')->nullable();
            $table->string('picture')->nullable();
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
        Schema::dropIfExists('catb_clients');
    }
}
