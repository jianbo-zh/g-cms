<?php

namespace App\Services\Thing\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\Thing\Model\StatsItemModel;

class StatsRepository extends Repository
{

    public function getStatsItems(int $thingId, array $fields=['*'])
    {
        $statsItems = StatsItemModel::where('thing_id', $thingId)
            ->select($fields)
            ->get();

        return $this->normalizeReturn($statsItems);
    }

    /**
     * 创建事物统计
     * @param int $thingId 事物编号
     * @param string $name 统计名称
     * @param array $dataConfig SQL查询数据库数据相关配置
     * @param array $chartConfig 统计图表相关配置
     * @return mixed 成功返回新建的统计信息
     */
    public function addStatsItem(int $thingId, string $name, array $dataConfig, array $chartConfig)
    {
        $stats = StatsItemModel::create([
            'thing_id' => $thingId,
            'name' => $name,
            'data_config' => $dataConfig,
            'chart_config' => $chartConfig,
        ]);

        return $this->normalizeReturn($stats);
    }

    /**
     * 更新事物统计
     *
     * @param int $statsItemId 事物统计项编号
     * @param null|string $name 统计名称
     * @param array|null $dataConfig SQL查询数据库数据相关配置
     * @param array|null $chartConfig 统计图表相关配置
     * @return bool|mixed 是否成功，成功则返回更新后的统计信息，失败false
     * @throws Exception
     */
    public function updateStatsItem(int $statsItemId, ?string $name=null, ?array $dataConfig=null, ?array $chartConfig=null)
    {
        if(is_null($name) || is_null($dataConfig) || is_null($chartConfig)){
            throw new Exception('更新数据统计不能都为空！');
        }

        $stats = StatsItemModel::findOrFail($statsItemId);
        if(!is_null($name)){
            $stats->name = $name;
        }
        if(!is_null($dataConfig)){
            $stats->data_config = $dataConfig;
        }
        if(!is_null($chartConfig)){
            $stats->chart_config = $chartConfig;
        }

        $result = $stats->save();

        return $result ? $this->normalizeReturn($stats) : false;
    }

    /**
     * 删除指定的事物统计项
     *
     * @param int $statsItemId 事物统计编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteStatsItem(int $statsItemId)
    {
        $stats = StatsItemModel::findOrFail($statsItemId);

        return $stats->delete();
    }

}