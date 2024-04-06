<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('invoice_type')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamp('invoice_date');
            $table->string('status')->nullable();
            $table->string('vendor' , 255)->nullable();
            $table->integer('vendor_tax_reg_number')->nullable();
            $table->string('vendor_address')->nullable();
            $table->string('vendor_country')->nullable();
            $table->string('vendor_phone')->nullable();
            $table->string('client')->nullable();
            $table->integer('client_tax_reg_number')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_country')->nullable();
            $table->string('client_phone')->nullable();

            $table->uuid('item_uuid');
            $table->string('items');
            $table->integer('item_qty');
            $table->decimal('item_price' , 18 ,3);
            $table->decimal('item_tax' , 18 ,3);
            $table->integer('item_tax_per');
            $table->decimal('item_discount' , 18 ,3);
            $table->integer('item_discount_per');
            $table->decimal('item_total' , 18 ,3);

            $table->decimal('total_items',18,2)->nullable();
            $table->decimal('tax',18,2)->default(0);
            $table->decimal('discount',18,2)->default(0);
            $table->decimal('total',18,2)->default(0);
            $table->text('notes')->nullable();
            $table->string('item_count')->nullable();
            $table->string('entry')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('invoices');
    }
}
