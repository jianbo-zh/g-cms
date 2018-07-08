<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')->comment('应用编号');
            $table->json('show_config')->comment('显示配置');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_stats` COMMENT '事物统计集合表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_stats');
    }
}
