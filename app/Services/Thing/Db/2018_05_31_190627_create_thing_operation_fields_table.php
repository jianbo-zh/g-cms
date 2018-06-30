<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingOperationFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_operation_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operation_id')->comment('操作编号');
            $table->unsignedInteger('field_id')->comment('字段编号');
            $table->string('is_show', 60)->comment('是否展示');
            $table->string('update_type')->comment('操作类型');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_operation_fields` COMMENT '事物操作字段表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_operation_fields');
    }
}
