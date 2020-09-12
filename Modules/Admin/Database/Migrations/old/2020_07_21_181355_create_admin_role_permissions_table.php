<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminSystemPermission;

class CreateAdminRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("admin_role_id");
            $table->unsignedSmallInteger("admin_perm_system_id");
            $table->unsignedInteger("system_perm_id");

            $table->foreign("admin_role_id")->references("admin_role_id")->on(AdminRole::class);
            $table->foreign("admin_perm_system_id")->references("admin_perm_system_id")->on(AdminPermissionSystem::class);
            $table->foreign("system_perm_id")->references("system_perm_id")->on(AdminSystemPermission::class);

            $table->index("admin_role_id");
            $table->index("admin_perm_system_id");
            $table->index("system_perm_id");

            $table->unsignedInteger("created_by");
            $table->index("created_by");
            $table->foreign("created_by")->references("admin_id")->on(Admin::class);

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
        Schema::dropIfExists('admin_role_permissions');
    }
}
