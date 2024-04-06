<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->string('type')->default(2);
            $table->string('name',35)->unique();
            $table->string('tax_code')->nullable()->unique();
            $table->string('country')->nullable();
            $table->string('gov')->nullable();
            $table->string('city')->nullable();
            $table->string('building_number')->nullable();
            $table->string('street')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('mobile')->nullable()->unique();
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
        Schema::dropIfExists('customers');
    }
}
