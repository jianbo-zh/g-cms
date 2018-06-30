<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingState2OperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_state_2_operation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_id')->comment('状态编号');
            $table->unsignedInteger('operation_id')->comment('操作编号');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_state_2_operation` COMMENT '事物状态与操作关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_state_2_operation');
    }
}
