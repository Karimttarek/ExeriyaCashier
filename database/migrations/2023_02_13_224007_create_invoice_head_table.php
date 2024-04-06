<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicehead', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->string('submission_uuid')->nullable()->unique();
            $table->string('document_uuid')->nullable()->unique();
            $table->integer('invoice_type');
            $table->string('invoice_number')->unique();
            $table->timestamp('invoice_date')->nullable();
            $table->string('internal_id');
            $table->string('document_type')->nullable()->default('I');
            $table->string('document_version')->nullable()->default('0.9');

            $table->string('taxpayer_activity_code');
            $table->string('issuer_id')->nullable();
            $table->string('issuer_type')->default('B');
            $table->string('issuer_name' ,35);
            $table->string('issuer_country')->nullable();
            $table->string('issuer_gov')->nullable();
            $table->string('issuer_city')->nullable();
            $table->string('issuer_building_number')->nullable();
            $table->string('issuer_street' ,35)->nullable();
            $table->string('issuer_email')->nullable();
            $table->string('issuer_mobile')->nullable();

            $table->string('customer_id')->nullable();
            $table->string('customer_type')->default('B');
            $table->string('customer_name' ,35);
            $table->string('customer_country');
            $table->string('customer_gov');
            $table->string('customer_city');
            $table->string('customer_building_number');
            $table->string('customer_street',35);

            $table->float('invoice_discount',18,5)->default('0')->nullable();
            $table->float('invoice_tax',18,5)->default('0')->nullable();


            $table->float('total_sales',18,5)->nullable();
            $table->float('total_items',18,5)->nullable();
            $table->float('total_net',18,5)->nullable();
            $table->float('total_items_discount',18,5)->nullable();
            $table->float('total_tax',18,5);
            $table->float('total_tax_table',18,5);
            $table->float('total_discount',18,5);
            $table->float('discount_after_tax',18,5);
            $table->float('total_after_discount',18,5);
            $table->float('total',18,5);
            $table->text('notes')->nullable();
            $table->integer('items_count')->nullable();
            $table->string('status')->default('Pending');
            $table->string('entry')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoicehead');
    }
}
