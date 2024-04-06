<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicedetails', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('invoice_number');
            $table->integer('invoice_type');
            $table->uuid('item_uuid')->nullable();
            $table->string('item');
            $table->string('description')->nullable();
            $table->string('code_type');
            $table->string('item_code')->nullable();
            $table->float('qty',18,5);
            $table->string('unit_type');
            $table->float('price' , 18,5);
            $table->string('currency');

            $table->float('tax', 18,5);
            $table->integer('tax_per');
            $table->float('extra_tax', 18,5);
            $table->text('tax_type')->nullable();
            $table->text('tax_sub_type')->nullable();
            $table->text('taxvalue' ,)->nullable();
            $table->text('taxPervalue')->nullable();

            $table->float('tax_table', 18,5)->default(0);
            $table->float('tax_table_per', 18)->default(0);

            $table->float('discount', 18,5)->default(0);
            $table->float('discount_per',18)->default(0);
            $table->float('net',18,5);
            $table->float('total_sales',18,5);
            $table->float('discount_after_tax', 18,5);
            $table->float('total' , 18,5);
            $table->integer('number');
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
        Schema::dropIfExists('invoicedetails');
    }
}
