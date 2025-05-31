<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('voucher_no',1000);
            $table->integer('vendor_id');
            $table->string('vendor_memo_no',1000);
            $table->integer('purchaser_id');
            $table->decimal('total_price',11,2)->default(0);
            $table->decimal('other_expense',11,2)->default(0)->nullable();
            $table->date('purchase_date');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('purchase_vouchers');
    }
}
