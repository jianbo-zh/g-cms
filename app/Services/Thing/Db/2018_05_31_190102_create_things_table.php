<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('things', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id')->comment('应用编号');
            $table->string('name', 60)->comment('名称');
            $table->string('description', 255)->comment('描述');
            $table->string('table_name', 60)->nullable()->comment('数据库表名');
            $table->softDeletes()->comment('删除时间');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `things` COMMENT '事物表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('things');
    }
}
