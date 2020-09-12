<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;

class CreateSystemAccessIpRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_access_ip_restrictions', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string("ip_location", 255);
            $table->string("ip_address", 50);
            $table->string("ip_address_key", 32);
            $table->text("description");

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
        Schema::dropIfExists('system_access_ip_restrictions');
    }
}
