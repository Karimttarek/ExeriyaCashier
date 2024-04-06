<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxSubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_sub_types', function (Blueprint $table) {
            $table->id();
            $table->string('Code',12);
            $table->string('Desc_en');
            $table->string('Desc_ar');
            $table->string('TaxtypeReference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_sub_types');
    }
}
