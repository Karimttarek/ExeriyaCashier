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
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('no');
            $table->integer('receipt_type'); // 1 => Expenses , 2 => Revenues
            $table->timestamp('receipt_date');
            $table->unsignedBigInteger('type_id');
            $table->string('type_name');
            $table->string('statement')->nullable();
            $table->float('value' , 18,3)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_details');
    }
};
