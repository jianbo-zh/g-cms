<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingMessageDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_message_definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_id')->comment('状态编号');
            $table->string('receiver_type')->comment('接收类型');
            $table->string('receiver_value')->comment('接收值');
            $table->string('content')->comment('消息内容');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_message_definitions` COMMENT '事物消息定义表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_message_definitions');
    }
}
