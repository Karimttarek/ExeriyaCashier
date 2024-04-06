<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('bar_code')->nullable()->after('item_code');

            $table->string('first_unit_type')->nullable();
            $table->float('first_unit_qty',18,3)->nullable();
            $table->float('first_unit_pur_price',18,3)->nullable();
            $table->float('first_unit_sell_price',18,3)->nullable();

            $table->float('second_unit_pur_price',18,3)->nullable();
            $table->float('second_unit_sell_price',18,3)->nullable();

            $table->float('third_unit_pur_price',18,3)->nullable();
            $table->float('third_unit_sell_price',18,3)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('bar_code');

            $table->dropColumn('first_unit_type');
            $table->dropColumn('first_unit_qty');
            $table->dropColumn('first_unit_pur_price');
            $table->dropColumn('first_unit_sell_price');

            $table->dropColumn('second_unit_pur_price');
            $table->dropColumn('second_unit_sell_price');
            $table->dropColumn('third_unit_pur_price');
            $table->dropColumn('third_unit_sell_price');
        });
    }
};
