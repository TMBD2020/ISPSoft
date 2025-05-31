<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('ref_voucher_id');
            $table->integer('ref_product_id');
            $table->integer('product_in')->default(0);
            $table->integer('product_out')->default(0);
            $table->integer('product_damage')->default(0);
            $table->integer('product_available')->default(0)->nullable();
            $table->enum('voucher_status',["purchase","requisition"])->nullable();
            $table->date('record_date')->nullable();
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
        Schema::dropIfExists('store_records');
    }
}
