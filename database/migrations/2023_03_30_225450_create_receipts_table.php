<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->string('no');
            $table->integer('receipt_type'); // 1 => Voucher , 2 => Cash
            $table->timestamp('receipt_date');
            $table->string('statement')->nullable();

            $table->string('receiver_uuid')->nullable();
            $table->string('receiver_name');
            $table->string('customer_uuid')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('supplier_uuid')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('exp_code')->nullable();
            $table->string('exp_name')->nullable();
            $table->string('receiver')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('check_no')->nullable();
            $table->string('value');
            $table->string('value_text')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('receipts');
    }
}
