<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;

class CreateIpRestrictionRevokedAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_restriction_revoked_admins', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger("admin_id");
            $table->text("remarks");

            $table->foreign("admin_id")->references("admin_id")->on(Admin::class);
            $table->index("admin_id");

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
        Schema::dropIfExists('ip_restriction_revoked_admins');
    }
}
