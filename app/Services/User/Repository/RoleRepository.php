<?php

namespace App\Services\User\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\User\Model\User2RoleModel;
use App\Services\User\Model\RoleModel;
use App\Services\User\Model\UserModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * 获取用户信息相关仓库
 *
 * Class UserRepository
 * @package App\Services\User\Repository
 */
class RoleRepository extends Repository
{

    /**
     * 获取角色列表
     *
     * @param bool|null $state 状态
     * @param array|null $fields 返回字段
     * @return array 角色列表
     */
    public function getPlatformRoles(?bool $state=null, ?array $fields=null)
    {
        $roles = RoleModel::whereNull('app_id')
            ->when(!is_null($state), function (Builder $query) use ($state){
                return $query->where('state', $state);
            })
            ->when(!is_null($fields), function(Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->orderBy('id', 'desc')
            ->get();

        return $this->normalizeReturn($roles);
    }

    /**
     * 创建平台角色
     *
     * @param string $name 角色名称
     * @param string $description 角色描述
     * @param bool $isEnable 是否可用
     * @param array $perms 权限列表
     * @return mixed
     */
    public function createPlatformRole(string $name, string $description, bool $isEnable, array $perms)
    {
        $role = RoleModel::create([
            'app_id'        => null,
            'name'          => $name,
            'description'   => $description,
            'perms'         => $perms,
            'state'         => $isEnable ? RoleModel::STATE_ENABLE : RoleModel::STATE_DISABLE,
        ]);

        return $this->normalizeReturn($role);
    }

    /**
     * 创建应用角色
     *
     * @param int $appId 应用编号
     * @param string $name 角色名称
     * @param string $description 角色描述
     * @param bool $isEnable 是否可用
     * @param array $perms 权限集
     * @return mixed
     */
    public function createAppRole(int $appId, string $name, string $description, bool $isEnable, array $perms)
    {
        $role = RoleModel::create([
            'app_id'        => $appId,
            'name'          => $name,
            'description'   => $description,
            'state'         => $isEnable ? RoleModel::STATE_ENABLE : RoleModel::STATE_DISABLE,
            'perms'         => $perms,
        ]);

        return $this->normalizeReturn($role);
    }

    /**
     * 更新平台角色
     *
     * @param int $roleId 角色编号
     * @param string $name 角色名称
     * @param string $description 角色描述
     * @param bool $isEnable 是否可用
     * @param array $perms 权限列表
     * @return mixed
     * @throws Exception
     */
    public function updatePlatformRole($roleId, ?string $name=null, ?string $description=null,
                                       ?bool $isEnable=null, ?array $perms=null)
    {
        if(is_null($name) && is_null($description) && is_null($isEnable) && is_null($perms)){
            throw new Exception('更新参数不能都为空！');
        }
        $role = RoleModel::findOrFail($roleId);
        if(! is_null($name)){
            $role->name = $name;
        }
        if(! is_null($description)){
            $role->description = $description;
        }
        if(! is_null($isEnable)){
            $role->state = $isEnable ? RoleModel::STATE_ENABLE : RoleModel::STATE_DISABLE;
        }
        if(! is_null($perms) && is_array($perms)){
            $role->perms = $perms;
        }

        $result = $role->save();

        return $result ? $this->normalizeReturn($role) : false;
    }

    /**
     * 更新应用角色
     *
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @param string $name 角色名称
     * @param string $description 角色描述
     * @param bool $isEnable 是否可用
     * @param array|null $perms 权限集合
     * @return mixed
     * @throws Exception
     */
    public function updateAppRole(int $appId, int $roleId, ?string $name=null, ?string $description=null,
                                  ?bool $isEnable=null, ?array $perms=null)
    {
        if(is_null($name) && is_null($description) && is_null($isEnable)){
            throw new Exception('更新参数不能都为空！');
        }
        $role = RoleModel::where('app_id', $appId)->where('id', $roleId)->first();
        if(! $role){
            throw new Exception('未找到对应的角色！');
        }

        if(! is_null($name)){
            $role->name = $name;
        }
        if(! is_null($description)){
            $role->description = $description;
        }
        if(! is_null($isEnable)){
            $role->state = $isEnable ? RoleModel::STATE_ENABLE : RoleModel::STATE_DISABLE;
        }
        if(! is_null($perms) && is_array($perms)){
            $role->perms = $perms;
        }

        $result = $role->save();

        return $result ? $this->normalizeReturn($role) : false;
    }

    /**
     * 获取平台角色
     *
     * @param int $roleId 角色编号
     * @return array 角色信息
     */
    public function getPlatformRole(int $roleId)
    {
        $role = RoleModel::find($roleId);

        return $this->normalizeReturn($role);
    }

    /**
     * 获取应用角色
     *
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @return array 角色信息
     */
    public function getAppRole(int $appId, int $roleId)
    {
        $role = RoleModel::where('app_id', $appId)
            ->where('id', $roleId)
            ->first();

        return $this->normalizeReturn($role);
    }

    /**
     * 删除平台角色
     *
     * @param int $roleId 角色编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deletePlatformRole(int $roleId)
    {
        $role = RoleModel::findOrFail($roleId);

        return $role->delete();
    }

    /**
     * 删除应用角色
     *
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteAppRole(int $appId, int $roleId)
    {
        $role = RoleModel::where('app_id', $appId)
            ->where('id', $roleId)
            ->first();

        if(! $role){
            throw new Exception('未找到对应的角色！');
        }

        return $role->delete();
    }

    /**
     * 获取角色下用户集
     *
     * @param int $roleId 角色编号
     * @param array $fields 返回字段
     * @param int $offset 偏移数值
     * @param int|null $limit 返回数量
     * @return array 用户集
     */
    public function getUsersBelongToRole(int $roleId, ?array $fields=null, int $offset=0, ?int $limit=null)
    {
        $role2Users = User2RoleModel::where('role_id', $roleId)->get();

        $userIds = [];
        foreach ($role2Users as $role2User){
            $userIds[] = $role2User['user_id'];
        }

        if(! $userIds){
            return [];
        }
        $users = UserModel::whereIn('id', $userIds)
            ->when($fields, function(Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->when($limit > 0, function (Builder $query) use ($offset, $limit){
                return $query->offset($offset)->limit($limit);
            })
            ->get();

        return $this->normalizeReturn($users);
    }

    /**
     * 绑定角色和用户
     *
     * @param int $roleId 角色编号
     * @param int $userId 用户编号
     * @return mixed
     */
    public function bindRole2User(int $roleId, int $userId)
    {
        $role2User = User2RoleModel::create([
            'role_id' => $roleId,
            'user_id' => $userId
        ]);

        return $this->normalizeReturn($role2User);
    }

    /**
     * 解除角色和用户绑定
     *
     * @param int $roleId 角色编号
     * @param int $userId 用户编号
     * @return bool|null 是否解除成功
     * @throws \Exception
     */
    public function unbindRole2User(int $roleId, int $userId)
    {
        $role2User = User2RoleModel::where('role_id', $roleId)
            ->where('user_id', $userId)
            ->first();

        if(! $role2User){
            return null;
        }

        return $role2User->delete();
    }

    /**
     * 取消所有角色到用户绑定，通过角色
     *
     * @param int $roleId 角色编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function unbindAllRole2UserByRole(int $roleId)
    {
        $result = User2RoleModel::where('role_id', $roleId)->delete();

        return $result;
    }

    /**
     * 取消所有角色到用户绑定，通过用户
     *
     * @param int $userId 用户编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function unbindAllUser2RoleByUser(int $userId)
    {
        $result = User2RoleModel::where('user_id', $userId)->delete();

        return $result;
    }

    /**
     * 获取属于某个角色的所有用户编号
     *
     * @param int $roleId 角色编号
     * @return array 用户编号集
     */
    public function getUserIdsBelongToRole(int $roleId)
    {
        $userIds = [];

        $result = User2RoleModel::where('role_id', $roleId)
            ->select('user_id')
            ->get();

        foreach ($result as $value){
            $userIds[] = $value['user_id'];
        }

        return $userIds;
    }

    /**
     * 获取应用角色列表
     *
     * @param int $appId 应用编号
     * @param array|null $condition 查询条件
     * @param array|null $fields 返回字段
     * @param int $offset 偏移数量
     * @param int|null $limit 返回数量
     * @return array 应用列表
     */
    public function getAppRoles(int $appId, ?array $condition=null, ?array $fields=null, int $offset=0, ?int $limit=null)
    {
        $roles = RoleModel::where('app_id', $appId)
            ->when($condition, function (Builder $query) use ($condition){
                if(isset($condition['state'])){
                    $query->where('state', !empty($condition['state']) ? RoleModel::STATE_ENABLE :
                        RoleModel::STATE_DISABLE);
                }
                return $query;
            })
            ->when(!empty($fields) && is_array($fields), function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->when($limit > 0, function (Builder $query) use ($offset, $limit){
                return $query->offset($offset)->limit($limit);
            })
            ->get();

        return $this->normalizeReturn($roles);
    }

    /**
     * 获取用户的平台角色列表
     *
     * @param int $userId 用户编号
     * @param array $condition 角色条件 [state：是否启用]
     * @param array|null $fields 返回字段
     * @return array|mixed
     */
    public function getPlatformRolesOfUser(int $userId, ?array $condition=null, array $fields=null)
    {
        $platformRoles = [];

        $user2role = User2RoleModel::where('user_id', $userId)->select('role_id')->get();
        if(! empty($user2role)){
            $userRoleIds = [];
            foreach ($user2role as $value){
                $userRoleIds[] = $value['role_id'];
            }

            $roles = RoleModel::whereIn('id', $userRoleIds)
                ->whereNull('app_id')
                ->when($condition, function (Builder $query) use ($condition){
                    if(isset($condition['state'])){
                        $query->where('state', !empty($condition['state']) ? RoleModel::STATE_ENABLE :
                            RoleModel::STATE_DISABLE);
                    }
                    return $query;
                })
                ->when(!empty($fields), function (Builder $query) use ($fields){
                    return $query->select($fields);
                })
                ->get();

            $platformRoles = $this->normalizeReturn($roles);
        }

        return $platformRoles;
    }

    /**
     * 获取用户的所有应用角色
     *
     * @param int $userId 用户编号
     * @param array $condition 角色条件 [state：是否启用]
     * @param array|null $fields 返回字段
     * @return array|mixed
     */
    public function getAppRolesOfUser(int $userId, ?array $condition=null, ?array $fields=null)
    {
        $appRoles = [];
        $user2role = User2RoleModel::where('user_id', $userId)->select('role_id')->get();
        if(! empty($user2role)){
            $userRoleIds = [];
            foreach ($user2role as $value){
                $userRoleIds[] = $value['role_id'];
            }

            $roles = RoleModel::whereIn('id', $userRoleIds)
                ->whereNotNull('app_id')
                ->when($condition, function (Builder $query) use ($condition){
                    if(isset($condition['state'])){
                        $query->where('state', !empty($condition['state']) ? RoleModel::STATE_ENABLE :
                            RoleModel::STATE_DISABLE);
                    }
                    return $query;
                })
                ->when(!empty($fields), function (Builder $query) use ($fields){
                    return $query->select($fields);
                })
                ->get();

            $appRoles = $this->normalizeReturn($roles);
        }

        return $appRoles;
    }


    /**
     * 获取用户的所有应用角色
     *
     * @param int $appId 应用编号
     * @param int $userId 用户编号
     * @param array $condition 角色条件 [state：是否启用]
     * @param array|null $fields 返回字段
     * @return array|mixed
     */
    public function getAppRolesOfAppUser(int $appId, int $userId, ?array $condition=null, array $fields=null)
    {
        $appRoles = [];
        $user2role = User2RoleModel::where('user_id', $userId)->select('role_id')->get();
        if(! empty($user2role)){
            $userRoleIds = [];
            foreach ($user2role as $value){
                $userRoleIds[] = $value['role_id'];
            }

            $roles = RoleModel::whereIn('id', $userRoleIds)
                ->where('app_id', $appId)
                ->when($condition, function (Builder $query) use ($condition){
                    if(isset($condition['state'])){
                        $query->where('state', !empty($condition['state']) ? RoleModel::STATE_ENABLE :
                            RoleModel::STATE_DISABLE);
                    }
                    return $query;
                })
                ->when(!empty($fields), function (Builder $query) use ($fields){
                    return $query->select($fields);
                })
                ->get();

            $appRoles = $this->normalizeReturn($roles);
        }

        return $appRoles;
    }
}