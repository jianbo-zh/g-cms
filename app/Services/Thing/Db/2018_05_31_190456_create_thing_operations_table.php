<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('thing_id')->comment('事物编号');
            $table->string('name', 20)->comment('操作名称');
            $table->string('operation_type', 10)->comment('操作类型');
            $table->string('operation_form', 10)->comment('操作形式');
            $table->softDeletes()->comment('删除时间');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_operations` COMMENT '事物操作表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_operations');
    }
}
