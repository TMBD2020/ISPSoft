<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isp_resellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('auth_id')->unique();
            $table->integer('network_id')->nullable();
            $table->integer('zone_id')->nullable();
            $table->integer('reseller_type')->default(0)->nullable();
            $table->string('reseller_id')->unique()->nullable();
            $table->string('reseller_name');
            $table->string('f_name')->nullable();
            $table->string('m_name')->nullable();
            $table->date('reseller_dob')->nullable();
            $table->text('reseller_permanent_add')->nullable();
            $table->text('reseller_present_add')->nullable();
            $table->string('personal_contact')->nullable();
            $table->string('office_contact')->nullable();
            $table->string('reseller_email')->nullable();
            $table->string('reseller_skype')->nullable();
            $table->string('reseller_image')->nullable();
            $table->string('reseller_logo')->nullable();
            $table->integer('reseller_activity')->default(1)->nullable();
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
        Schema::dropIfExists('companies');
    }
}
