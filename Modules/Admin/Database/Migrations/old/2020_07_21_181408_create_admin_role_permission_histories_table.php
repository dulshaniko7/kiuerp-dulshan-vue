<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\AdminPermissionSystem;

class CreateAdminRolePermissionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_permission_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("admin_role_id");
            $table->unsignedSmallInteger("admin_perm_system_id");
            $table->text("remarks");
            $table->json("invoked_permissions");
            $table->json("revoked_permissions");

            $table->foreign("admin_role_id")->references("admin_role_id")->on(AdminRole::class);
            $table->foreign("admin_perm_system_id")->references("admin_perm_system_id")->on(AdminPermissionSystem::class);

            $table->index("admin_role_id");
            $table->index("admin_perm_system_id");

            $table->unsignedInteger("updated_by");
            $table->index("updated_by");
            $table->foreign("updated_by")->references("admin_id")->on(Admin::class);

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
        Schema::dropIfExists('admin_role_permission_histories');
    }
}
