<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stats_id')->comment('统计编号');
            $table->string('name', 60)->comment('名称');
            $table->string('graph_name')->comment('图形名称');
            $table->mediumText('config_code')->comment('配置代码');
            $table->unsignedTinyInteger('width')->comment('宽度');
            $table->unsignedTinyInteger('sort_num')->comment('排序号');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `stats_items` COMMENT '统计项目表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats_items');
    }
}
