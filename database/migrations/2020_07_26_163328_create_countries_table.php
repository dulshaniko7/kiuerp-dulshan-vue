<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->smallIncrements("country_id");
            $table->string("country_name", 255);
            $table->string("country_code", 2)->unique();
            $table->string("country_code_alt", 3)->nullable();
            $table->string("calling_code", 5)->nullable();
            $table->string("currency_code", 10)->nullable();
            $table->string("citizenship", 255)->nullable();
            $table->unsignedTinyInteger("currency_decimals")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
