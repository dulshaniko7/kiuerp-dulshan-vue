<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;

class CreateAdminLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_login_histories', function (Blueprint $table) {
            $table->bigIncrements('admin_login_history_id');
            $table->unsignedInteger("admin_id");
            $table->string("login_ip", 50)->nullable();
            $table->unsignedSmallInteger("country_id")->nullable();
            $table->string("city", 255)->nullable();
            $table->string("login_failed_reason", 255)->nullable();
            $table->unsignedTinyInteger("online_status");
            $table->dateTime("last_activity_at");
            $table->dateTime("sign_in_at");
            $table->unsignedTinyInteger("sign_out_type")->nullable();
            $table->dateTime("sign_out_at")->nullable();

            $table->foreign("admin_id")->references("admin_id")->on(Admin::class);
            $table->index("admin_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_login_histories');
    }
}
