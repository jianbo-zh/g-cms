<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id')->nullable()->comment('应用编号');
            $table->string('name', 60)->comment('名称');
            $table->string('description')->comment('描述');
            $table->json('perms')->comment('权限');
            $table->tinyInteger('state')->comment('状态');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `user_roles` comment '角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
