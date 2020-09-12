<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;

class CreateAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->integerIncrements('admin_role_id')->unsigned();
            $table->string("role_name", 100);
            $table->text("description")->nullable(true);
            $table->json("allowed_roles")->nullable(true)->comment("Allowed user roles to create by this user role. JSON array of values.");
            $table->unsignedTinyInteger("role_status");
            $table->text("disabled_reason")->nullable(true)->comment("If user role has been disabled, then reason for that. This value will be displayed to the user when he is trying to sign in");

            $table->unsignedInteger("created_by");
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->foreign("created_by")->references("admin_id")->on(Admin::class);
            $table->foreign("updated_by")->references("admin_id")->on(Admin::class);
            $table->foreign("deleted_by")->references("admin_id")->on(Admin::class);

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
        Schema::dropIfExists('admin_roles');
    }
}
