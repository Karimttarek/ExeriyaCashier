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
            $table->string('second_unit_type')->nullable();
            $table->float('second_unit_qty',18,3)->nullable();

            $table->string('third_unit_type')->nullable();
            $table->float('third_unit_qty',18,3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('second_unit_type');
            $table->dropColumn('second_unit_qty');

            $table->dropColumn('third_unit_type');
            $table->dropColumn('third_unit_qty');
        });
    }
};
