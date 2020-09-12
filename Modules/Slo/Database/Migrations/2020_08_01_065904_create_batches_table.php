<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('batch_id');

            $table->string('batch_name', 100);

            $table->string('batch_code', 100);
            $table->string('portfolio', 255)->nullable();
            $table->smallInteger('max_student')->nullable();
            $table->date('batch_start_date');
            $table->date('batch_end_date');
            $table->smallInteger('loan')->nullable();
            $table->unsignedSmallInteger('intake')->nullable();
            $table->tinyInteger('approved')->default(0);

            $table->dateTime("updated_on")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();
            $table->softDeletes();

            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->unsignedBigInteger('course_id');

            $table->unsignedBigInteger('batch_type');
            $table->foreign('batch_type')->references('id')->on('batch_types')->onDelete('cascade');
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
        Schema::dropIfExists('batches');
    }
}
