<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminPermissionModule;

class CreateAdminPermissionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission_groups', function (Blueprint $table) {
            $table->integerIncrements('admin_perm_group_id');
            $table->unsignedInteger("admin_perm_module_id");
            $table->string("group_name", 255);
            $table->unsignedTinyInteger("group_status");
            $table->text("remarks");

            $table->unsignedInteger("created_by");
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->foreign("admin_perm_module_id")->references("admin_perm_module_id")->on(AdminPermissionModule::class);
            $table->foreign("created_by")->references("admin_id")->on(Admin::class);
            $table->foreign("updated_by")->references("admin_id")->on(Admin::class);
            $table->foreign("deleted_by")->references("admin_id")->on(Admin::class);

            $table->index("admin_perm_module_id");
            $table->index("created_by");
            $table->index("updated_by");
            $table->index("deleted_by");

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
        Schema::dropIfExists('admin_permission_groups');
    }
}
