<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingStatsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thing_stats_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thing_id')->comment('事物编号');
            $table->string('name')->comment('统计名称');
            $table->json('data_config')->comment('数据配置');
            $table->json('chart_config')->comment('图表配置');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `thing_fields` COMMENT '事物统计项表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thing_stats_item');
    }
}
