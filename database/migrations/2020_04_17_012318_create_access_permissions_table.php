<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('role_id');
            $table->integer('module_id');
            $table->integer('sub_module_id');
            $table->integer('read_access')->nullable()->default(0);
            $table->integer('write_access')->nullable()->default(0);
            $table->integer('update_access')->nullable()->default(0);
            $table->integer('delete_access')->nullable()->default(0);
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
        Schema::dropIfExists('access_permissions');
    }
}
