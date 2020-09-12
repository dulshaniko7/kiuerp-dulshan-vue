<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminLoginHistory;

class CreateAdminActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_login_history_id');
            $table->unsignedInteger("admin_id");
            $table->string("event", 20)->nullable();
            $table->text("activity");
            $table->json("activity_old_data");
            $table->json("activity_new_data");
            $table->string("activity_model_name", 255);
            $table->unsignedBigInteger("activity_model");
            $table->dateTime("activity_at");

            $table->foreign("admin_id")->references("admin_id")->on(Admin::class);
            $table->foreign("admin_login_history_id")->references("admin_login_history_id")->on(AdminLoginHistory::class);

            $table->index("admin_id");
            $table->index("admin_login_history_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_activities');
    }
}
