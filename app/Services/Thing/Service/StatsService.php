<?php

namespace App\Services\Thing\Service;

use App\Services\_Base\Service;
use App\Services\Thing\Repository\StatsRepository;

class StatsService extends Service
{
    /**
     * @var StatsRepository
     */
    protected $statsRepo;

    /**
     * StatsService constructor.
     */
    protected function __construct()
    {
        $this->statsRepo = StatsRepository::instance();
    }

    /**
     * 创建事物统计项
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param string $name 统计名称
     * @param array $dataConfig 获取数据库数据相关配置
     * @param array $chartConfig 统计图表相关配置
     * @return mixed 新创建的统计
     */
    public function addStatsItem(string $authCode, int $thingId, string $name, array $dataConfig, array $chartConfig)
    {
        return $this->statsRepo->addStatsItem($thingId, $name, $dataConfig, $chartConfig);
    }

    /**
     * 更新事物统计项
     *
     * @param string $authCode 授权码
     * @param int $statsItemId 统计项编号
     * @param null|string $name 统计名称
     * @param array|null $dataConfig 获取数据库数据相关配置
     * @param array|null $chartConfig 统计图表相关配置
     * @return bool|mixed 是否成功，成功返回更新后的统计项，失败返回false
     * @throws \App\Services\_Base\Exception
     */
    public function updateStatsItem(string $authCode, int $statsItemId, ?string $name=null, ?array $dataConfig=null,
                                    ?array $chartConfig=null)
    {
        return $this->statsRepo->updateStatsItem($statsItemId, $name, $dataConfig, $chartConfig);
    }

    /**
     * 删除事物统计项
     *
     * @param string $authCode 授权码
     * @param int $statsItemId 统计项编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteStatsItem(string $authCode, int $statsItemId)
    {
        $result = $this->statsRepo->deleteStatsItem($statsItemId);

        return $result !== false ? true : false;
    }

}