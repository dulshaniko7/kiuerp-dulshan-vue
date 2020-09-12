<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\Admin;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->integerIncrements('admin_id');
            $table->unsignedInteger("admin_role_id")->nullable();
            $table->unsignedInteger("employee_id")->nullable();
            $table->unsignedInteger("lecturer_id")->nullable();
            $table->string("name", 255);
            $table->string("email")->unique();
            $table->string("password", 60);
            $table->string("image")->nullable();
            $table->unsignedTinyInteger("default_admin")->default('0');
            $table->unsignedTinyInteger("status")->default("1");
            $table->rememberToken();

            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            /*$table->foreign("employee_id")->references("employee_id")->on(Employee::class);
            $table->index("employee_id");

            $table->foreign("lecturer_id")->references("lecturer_id")->on(Lecturer::class);
            $table->index("lecturer_id");*/

            $table->foreign("admin_role_id")->references("admin_role_id")->on(AdminRole::class);
            $table->index("admin_role_id");

            $table->foreign("created_by")->references("admin_id")->on(Admin::class);
            $table->foreign("updated_by")->references("admin_id")->on(Admin::class);
            $table->foreign("deleted_by")->references("admin_id")->on(Admin::class);

            $table->index("created_by");
            $table->index("updated_by");
            $table->index("deleted_by");

            $table->timestamps();
            $table->softDeletes();
        });

        //add default system administrator to the system
        Admin::create([
            'name' => "Default System Admin",
            'email' => "test@test.com",
            'password' => "test@123",
            'admin_role_id' => 0,
            'default_admin' => "1",
            'status' => "1",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
