<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('auth_id')->unique();
            $table->string('client_id');
            $table->string('client_name');
            $table->integer('company_id');
            $table->integer('network_id');
            $table->integer('pop_id');
            $table->integer('zone_id');
            $table->integer('box_id');
            $table->integer('package_id');
            $table->integer('payment_dateline');
            $table->date('termination_date')->nullable();
            $table->integer('billing_date')->nullable();
            $table->string('cell_no');
            $table->integer('technician_id');
            $table->integer('payment_id');
            $table->float('signup_fee')->nullable();
            $table->float('permanent_discount')->default(0)->nullable();
            $table->integer('payment_alert_sms')->nullable()->default(0);
            $table->integer('payment_conformation_sms')->nullable()->default(0);
            $table->string('alter_cell_no_1')->nullable();
            $table->string('alter_cell_no_2')->nullable();
            $table->string('alter_cell_no_3')->nullable();
            $table->string('alter_cell_no_4')->nullable();
            $table->text('address')->nullable();
            $table->string('thana')->nullable();
            $table->date('join_date')->nullable();
            $table->string('occupation')->nullable();
            $table->string('email')->nullable();
            $table->string('nid')->nullable();
            $table->string('previous_isp')->nullable();
            $table->integer('client_type_id')->nullable();
            $table->integer('connectivity_id')->nullable();
            $table->string('pppoe_username')->nullable();
            $table->string('pppoe_password')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('gpon_mac_address')->nullable();
            $table->string('olt_interface')->nullable();
            $table->string('receive_power')->nullable();
            $table->string('connection_mode')->nullable()->default(1);
            $table->tinyInteger('lock_status')->nullable()->default(0);
            $table->smallInteger('lock_sms')->nullable()->default(0);
            $table->timestamp('lock_datetime')->nullable();
            $table->timestamp('lock_commit_pay_deadline')->nullable();
            $table->integer('cable_id')->nullable();
            $table->string('required_cable')->nullable();
            $table->text('user_and_fiber_status')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
