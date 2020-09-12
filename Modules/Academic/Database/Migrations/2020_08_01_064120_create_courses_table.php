<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('course_id');
            $table->string('course_name');
            $table->tinyInteger('course_category');
            $table->string('course_code');
            $table->string('abbrevation')->nullable();
            $table->tinyInteger('course_du_years')->nullable();
            $table->tinyInteger('course_du_months')->nullable();
            $table->tinyInteger('course_du_dates')->nullable();
            $table->tinyInteger('course_du_years_ex')->nullable();
            $table->tinyInteger('course_du_months_ex')->nullable();
            $table->tinyInteger('course_du_dates_ex')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();


            $table->unsignedInteger('dept_id');
            $table->foreign('dept_id')->references('dept_id')->on('departments')->onDelete('cascade');

            // $table->unsignedInteger('slqf_id');
            //$table->foreign('slqf_id')->references('slqf_id')->on('slqfs')->onDelete('cascade');
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
        Schema::dropIfExists('courses');
    }
}
