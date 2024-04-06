<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code_type')->default('EGS');
            $table->string('codeUsageRequestId')->nullable();
            $table->string('parent_code')->nullable();
            $table->string('item_code');
            $table->string('name')->unique();
            $table->string('name_ar')->unique();
            $table->string('description',999)->nullable();
            $table->string('description_ar',999)->nullable();

            $table->string('type_code')->nullable();
            $table->string('type_desc')->nullable();

            $table->string('item_type')->nullable()->default(1);

            $table->integer('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->decimal('purchase_price' , 18 ,3)->required();
            $table->decimal('sell_price' , 18 ,3)->nullable()->default('0');

            $table->string('currency_code')->nullable();
            $table->string('currency_desc')->nullable();

            $table->text('tax_code')->nullable();
            $table->text('tax')->nullable();
            $table->decimal('discount' , 18 ,3)->nullable()->default('0');
            $table->integer('stock')->nullable()->default('0');
            $table->string('active')->default('Pending');
            $table->date('active_from');
            $table->date('active_to');
            $table->string('request_reason')->nullable();
            $table->string('entry')->nullable();
            $table->tinyInteger('ported')->default(0);
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
        Schema::dropIfExists('products');
    }
}
