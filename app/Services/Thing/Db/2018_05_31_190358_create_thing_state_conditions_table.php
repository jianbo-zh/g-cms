<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingStateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_state_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_id')->comment('状态编号');
            $table->unsignedInteger('field_id')->comment('字段编号');
            $table->string('symbol', 60)->comment('操作符');
            $table->string('value')->nullable()->comment('操作值');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_state_conditions` COMMENT '事物状态条件表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_state_conditions');
    }
}
