<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户编号');
            $table->string('name', 60)->comment('名称');
            $table->string('description')->comment('描述');
            $table->tinyInteger('state')->comment('状态');
            $table->softDeletes()->comment('删除时间');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `apps` comment '应用表';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
