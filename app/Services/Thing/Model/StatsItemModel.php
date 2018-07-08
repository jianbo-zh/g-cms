<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 事物统计项集合
 * App\Services\Thing\Model\StatsItemModel
 *
 * @mixin \Eloquent
 */
class StatsItemModel extends Model
{
    protected $table = 'thing_stats_items';

    protected $fillable = ['thing_id', 'name', 'data_config', 'chart_config'];

    protected $casts = [
        'data_config'   => 'array',
        'chart_config'  => 'array',
    ];

}
