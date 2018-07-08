<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 事物的统计集合模型
 *
 * App\Services\Thing\Model\StatsModel
 *
 * @mixin \Eloquent
 */
class StatsModel extends Model
{
    protected $table = 'thing_stats';

    protected $fillable = ['app_id', 'show_config'];

    public function conditions()
    {
        $this->hasMany(StatsItemModel::class, 'stats_id');
    }

}
