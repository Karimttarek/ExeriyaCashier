<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_profile', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('tax_rCode')->nullable();
            $table->string('tax_aCode')->nullable();
            $table->string('owner')->nullable();
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_profile');
    }
}
