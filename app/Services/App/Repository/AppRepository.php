<?php
/**
 * Created by PhpStorm.
 * User: Tipine
 * Date: 2018/5/30
 * Time: 15:59
 */

namespace App\Services\App\Repository;


use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\App\Model\AppModel;
use App\Services\App\Model\AppRole2UserModel;
use App\Services\App\Model\AppRoleModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AppRepository extends Repository
{
    /**
     * 添加一个用户应用
     *
     * @param int $userId 用户编号
     * @param string $name 名称
     * @param string $dec 描述
     * @param bool $isEnable 是否启用
     * @return array 应用信息
     */
    public function addApp(int $userId, string $name, string $dec, bool $isEnable)
    {
        $app = AppModel::create([
            'user_id'       => $userId,
            'name'          => $name,
            'description'   => $dec,
            'state'         => $isEnable ? AppModel::STATE_ENABLE : AppModel::STATE_DISABLE,
        ]);

        return $this->normalizeReturn($app);
    }

    /**
     * 获取用户的应用数据
     *
     * @param int $userId 用户编号
     * @param array $orderBy 排序依据：["id", "asc|desc"]
     * @param array $fields 返回字段：["id", "name", "description", "state", "created_at", "updated_at"]
     * @param int $offset 偏移数量
     * @param int $limit 返回数量
     * @return array 应用列表
     */
    public function getAppsOfUser(int $userId, array $condition=[], array $fields=[], array $orderBy=[], int $offset=0, int $limit=0)
    {
        $condition['userId'] = $userId;
        return $this->normalizeReturn($this->commonGetApps($condition, $fields, $orderBy, $offset, $limit));
    }

    /**
     * 获取应用数据
     *
     * @param array|null $fields 返回字段：["id", "name", "description", "state", "created_at", "updated_at"]
     * @param array $orderBy 排序依据：["id", "asc|desc"]
     * @param int $offset 偏移数量
     * @param int $limit 返回数量
     * @return array 应用列表
     */
    public function getApps(array $fields=null, array $orderBy=[], int $offset=0, int $limit=0)
    {
        return $this->normalizeReturn($this->commonGetApps([], $fields, $orderBy, $offset, $limit));
    }

    /**
     * 获取应用数据
     *
     * @param array|null 查询条件
     * @param array|null $fields 返回字段：["id", "name", "description", "state", "created_at", "updated_at"]
     * @param array $orderBy 排序依据：["id", "asc|desc"]
     * @param int $offset 偏移数量
     * @param int $limit 返回数量
     * @return array 应用列表
     */
    public function getAppsByCondition(array $condition=null, array $fields=null, array $orderBy=[], int $offset=0,
                                       int $limit=0)
    {
        return $this->normalizeReturn($this->commonGetApps($condition, $fields, $orderBy, $offset, $limit));
    }

    /**
     * 获取应用状态映射关系
     *
     * @return array 应用状态映射
     */
    public function getMapOfAppState()
    {
        return AppModel::getStateMap();
    }

    /**
     * 通过应用编号获取应用
     *
     * @param int $appId 应用编号
     * @return array 应用
     */
    public function getApp(int $appId)
    {
        $app = AppModel::find($appId);

        return $this->normalizeReturn($app);
    }

    /**
     * 更新应用信息
     * 字段为null则表示不更新该字段
     *
     * @param int $appId 应用编号
     * @param int|null $userId 管理员
     * @param string|null $name 名称
     * @param string|null $dec 描述
     * @param bool|null $isEnable 是否可用
     * @return array|false 成功则返回更新后的应用，失败false
     */
    public function updateApp(int $appId, int $userId=null, string $name=null, string $dec=null, bool $isEnable=null)
    {
        $app = AppModel::findOrFail($appId);
        if(!is_null($userId)){
            $app->user_id = $userId;
        }
        if(!is_null($name)){
            $app->name = $name;
        }
        if(!is_null($dec)){
            $app->description = $dec;
        }
        if(!is_null($isEnable)){
            $app->state = $isEnable ? AppModel::STATE_ENABLE : AppModel::STATE_DISABLE;
        }

        $result = $app->save();

        return $result ? $this->normalizeReturn($app) : false;
    }


    /**
     * 软删除应用
     *
     * @param int $appId 应用编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteApp(int $appId)
    {
        $app = AppModel::findOrFail($appId);

        return $app->delete() ? true : false;
    }

    /**
     * 获取用户应用数据的公共方法
     *
     * @param array|null $condition 查询条件  [userId, name, state]
     * @param array $fields 返回字段
     * @param array $orderBy 排序方式
     * @param int $offset 偏移数量
     * @param int $limit 返回数量
     * @return Collection 用户应用集合
     */
    private function commonGetApps(array $condition=[], array $fields=[], array $orderBy=[], int $offset=0,
                                   int $limit=0)
    {
        $apps = AppModel::when(!empty($condition), function (Builder $query) use ($condition){
                if(isset($condition['userId'])){
                    $query->where('user_id', $condition['userId']);
                }
                if(isset($condition['name'])){
                    $query->where('name', $condition['name']);
                }
                if(isset($condition['state'])){
                    $query->where('state', !empty($condition['state']) ? AppModel::STATE_ENABLE :
                        AppModel::STATE_DISABLE);
                }
                return $query;
            })
            ->when($fields, function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->when($orderBy, function (Builder $query) use ($orderBy){
                return $query->orderBy(...$orderBy);
            })
            ->when($limit, function (Builder $query) use ($offset, $limit){
                return $query->offset($offset)->limit($limit);
            })
            ->get();

        return $apps;
    }
}