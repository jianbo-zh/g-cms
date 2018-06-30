<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('thing_id')->comment('事物编号');
            $table->string('name', 60)->comment('字段名');
            $table->string('name_old', 60)->comment('旧字段名');
            $table->string('storage_type', 60)->comment('存储类型');
            $table->string('show_type', 60)->comment('展示类型');
            $table->string('show_options', 255)->comment('展示选项');
            $table->tinyInteger('is_list')->comment('是否列表显示');
            $table->tinyInteger('is_search')->comment('是否搜索条件');
            $table->tinyInteger('state')->comment('迁移状态');
            $table->string('comment')->comment('备注');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_fields` COMMENT '事物字段表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_fields');
    }
}
