<?php
namespace App\Services\App\Service;

use App\Services\_Base\Service;
use App\Services\App\Repository\AppRepository;


/**
 * 用户应用服务
 *
 * Class UserRoleAuthService
 * @package App\Services\User\Service
 */
class AppService extends Service
{

    /**
     * 获取用户拥有的应用
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @param array $condition 查询条件
     * @return array 用户拥有的应用数据
     */
    public function getAppsOfUser(string $authCode, int $userId, array $condition=[])
    {
        $appRepo = AppRepository::instance();

        if($userId <= 0){
            throw new \InvalidArgumentException('用户编号必须大于0！');
        }

        return $appRepo->getAppsOfUser($userId, $condition, ['id', 'name', 'description', 'state', 'created_at']);
    }

    /**
     * 获取用户拥有的所有应用编号集
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @param array $condition 查询条件
     * @return array 应用编号集
     */
    public function getAppIdsOfUser(string $authCode, int $userId, array $condition=[])
    {
        $appRepo = AppRepository::instance();

        $appIds = [];
        $apps = $appRepo->getAppsOfUser($userId, $condition, ['id']);
        foreach ($apps as $app){
            $appIds[] = $app['id'];
        }

        return $appIds;
    }

    /**
     * 通过编号获取应用详情
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @return array 应用信息
     */
    public function getApp(string $authCode, int $appId)
    {
        $appRepo = AppRepository::instance();

        return $appRepo->getApp($appId);
    }

    /**
     * 获取用户应用列表
     *
     * @param string$authCode 授权码
     * @param int $offset 偏移数量
     * @param int $limit 返回数量
     * @return array 应用列表
     */
    public function getApps(string $authCode, $offset=0, $limit=0)
    {
        $appRepo = AppRepository::instance();

        return $appRepo->getApps(['id', 'name', 'description', 'state', 'created_at'], ['id', 'desc'], $offset, $limit);
    }

    /**
     * 根据查询条件查找应用列表
     *
     * @param string $authCode 授权码
     * @param array $condition 查询条件 [userId, name, state]
     * @param int $offset 偏移数量
     * @param int $limit 返回数量
     * @return array 应用列表
     */
    public function getAppsByCondition(string $authCode, array $condition, $offset=0, $limit=0)
    {
        $appRepo = AppRepository::instance();

        return $appRepo->getAppsByCondition($condition, ['*'], ['id', 'desc'], $offset, $limit);
    }

    /**
     * 更新应用信息
     *
     * @param string $authCode
     * @param int $appId 应用编号
     * @param int|null $userId 用户编号
     * @param string|null $name 名称
     * @param string|null $dec 描述
     * @param bool|null $isEnable 是否可用
     * @return array|false 成功则返回更新后的应用，失败false
     */
    public function updateApp(string $authCode, int $appId, int $userId=null, string $name=null, string $dec=null,
                              bool $isEnable=null)
    {
        $appRepo = AppRepository::instance();

        return $appRepo->updateApp($appId, $userId, $name, $dec, $isEnable);
    }

    /**
     * 删除应用（软删除）
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteApp(string $authCode, int $appId)
    {
        $appRepo = AppRepository::instance();

        return $appRepo->deleteApp($appId);
    }

    /**
     * 添加一个应用
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @param string $name 名称
     * @param string $dec 描述
     * @param bool $isEnable 是否启用
     * @return array 应用信息
     */
    public function addApp(string $authCode, int $userId, string $name, string $dec, bool $isEnable)
    {
        $appRepo = AppRepository::instance();

        return $appRepo->addApp($userId, $name, $dec, $isEnable);
    }
}