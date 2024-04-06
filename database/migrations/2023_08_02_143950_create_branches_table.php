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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('country')->nullable();
            $table->string('governorate')->nullable();
            $table->string('city')->nullable();
            $table->text('building_number')->nullable();
            $table->text('street')->nullable();
            $table->string('img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
