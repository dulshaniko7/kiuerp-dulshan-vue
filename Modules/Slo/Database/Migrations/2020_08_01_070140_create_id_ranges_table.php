<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('id_ranges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description', 100);
            $table->integer('start');
            $table->integer('end');

            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');

            $table->softDeletes();

            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

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
        Schema::dropIfExists('id_ranges');
    }
}
