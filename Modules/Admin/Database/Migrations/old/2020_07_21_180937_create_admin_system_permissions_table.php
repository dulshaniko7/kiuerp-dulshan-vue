<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminPermissionGroup;

class CreateAdminSystemPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_system_permissions', function (Blueprint $table) {
            $table->integerIncrements('system_perm_id');
            $table->unsignedInteger("admin_perm_group_id");
            $table->string("permission_title", 255);
            $table->string("permission_action", 1000);
            $table->string("permission_key", 32);
            $table->unsignedTinyInteger("permission_status");
            $table->text("disabled_reason")->nullable();

            $table->unsignedInteger("created_by");
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->foreign("admin_perm_group_id")->references("admin_perm_group_id")->on(AdminPermissionGroup::class);
            $table->foreign("created_by")->references("admin_id")->on(Admin::class);
            $table->foreign("updated_by")->references("admin_id")->on(Admin::class);
            $table->foreign("deleted_by")->references("admin_id")->on(Admin::class);

            $table->index("admin_perm_group_id");
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
        Schema::dropIfExists('admin_system_permissions');
    }
}
