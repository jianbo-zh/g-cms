<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_states', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('thing_id')->comment('事物编号');
            $table->string('name', 60)->comment('名称');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_states` COMMENT '事物状态表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_states');
    }
}
