<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('auth_id')->unique();
            $table->string('emp_id',255)->unique();
            $table->string('emp_name',255);
            $table->string('emp_father',255);
            $table->string('emp_mother',255);
            $table->string('emp_mobile',50);
            $table->string('emp_email',255)->nullable();
            $table->text('emp_present_address');
            $table->text('emp_permanent_address');
            $table->string('emp_photo',255)->nullable();
            $table->integer('emp_department_id');
            $table->integer('emp_designation_id');
            $table->date('emp_join_date');
            $table->date('emp_resign_date')->nullable();
            $table->tinyInteger('is_resign')->default(0);
            $table->string('relative_name');
            $table->string('relative_mobile');
            $table->string('relative_nid')->nullable();
            $table->string('relative_relation');
            $table->text('relative_present_add');
            $table->text('relative_permanent_add')->nullable();
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('employees');
    }
}
