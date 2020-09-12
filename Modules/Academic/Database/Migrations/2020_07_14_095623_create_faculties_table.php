<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->integerIncrements('faculty_id');
            $table->string('faculty_code', 255);
            $table->string('faculty_name', 255);
            $table->string('color_code', 18);
            $table->unsignedTinyInteger('faculty_status')->default(0);

            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            /*$table->foreign("created_by")->references("admin_id")->on(\Modules\Admin\Entities\Admin::class);
            $table->foreign("updated_by")->references("admin_id")->on(\Modules\Admin\Entities\Admin::class);
            $table->foreign("deleted_by")->references("admin_id")->on(\Modules\Admin\Entities\Admin::class);*/

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculties');
    }
}
