<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;

class CreateAdminPermissionChangeRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission_change_remarks', function (Blueprint $table) {
            $table->bigIncrements('admin_perm_change_remark_id');
            $table->text("remarks");
            $table->unsignedInteger("created_by");

            $table->foreign("created_by")->references("admin_id")->on(Admin::class);
            $table->index("created_by");

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
        Schema::dropIfExists('admin_permission_change_remarks');
    }
}
