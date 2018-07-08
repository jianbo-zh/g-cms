<?php
namespace App\Services\User\Service;

use App\Services\_Base\Exception;
use App\Services\_Base\Service;
use App\Services\Thing\Repository\OperationRepository;
use App\Services\User\Logic\RoleLogic;
use App\Services\User\Logic\UserLogic;
use App\Services\User\Repository\RoleRepository;
use App\Services\User\Repository\UserRepository;

/**
 * 用户、角色、权限服务
 *
 * Class RoleService
 * @package App\Services\User\Service
 */
class RoleService extends Service
{
    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @var RoleRepository
     */
    protected $roleRepo;

    /**
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * @var OperationRepository
     */
    protected $thingOperationRepo;

    /**
     * @var UserLogic
     */
    protected $userLogic;

    protected function __construct()
    {
        $this->userRepo = UserRepository::instance();
        $this->roleRepo = RoleRepository::instance();
        $this->roleLogic = RoleLogic::instance();
        $this->userLogic = UserLogic::instance();
        $this->thingOperationRepo = OperationRepository::instance();
    }

    /**
     * 获取平台角色列表
     *
     * @param string $authCode 授权码
     * @param int $state 状态 (1: 启用, 0: 禁用)
     * @return array 角色列表
     */
    public function getPlatformRoles(string $authCode, int $state=null)
    {
        $roles = $this->roleRepo->getPlatformRoles($state, ['id', 'name', 'description', 'state',
            'created_at']);

        return $roles;
    }

    /**
     * 获取应用角色列表
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param array|null $condition 查询条件
     * @param int $offset 偏移数量
     * @param int|null $limit 返回数量
     * @return array 应用列表
     */
    public function getAppRoles(string $authCode, int $appId, array $condition=[], int $offset=0, int $limit=20)
    {
        $roles = $this->roleRepo->getAppRoles($appId, $condition, ['id', 'name', 'description', 'state',
            'created_at'], $offset, $limit);

        return $roles;
    }

    /**
     * 创建平台角色
     *
     * @param string $authCode 授权码
     * @param string $name 角色名称
     * @param string $description 角色描述
     * @param bool $isEnable 是否可用
     * @param array $perms 权限列表
     * @return mixed
     */
    public function addPlatformRole(string $authCode, string $name, string $description, bool $isEnable,
                                    array $perms)
    {
        return $this->roleRepo->createPlatformRole($name, $description, $isEnable, $perms);
    }

    /**
     * 创建应用角色
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param string $name 角色名称
     * @param string $description 角色描述
     * @param bool $isEnable 是否可用
     * @param array $perms 权限集
     * @return mixed
     */
    public function addAppRole(string $authCode, int $appId, string $name, string $description, bool $isEnable,
                               array $perms)
    {
        return $this->roleRepo->createAppRole($appId, $name, $description, $isEnable, $perms);
    }

    /**
     * 更新平台角色
     *
     * @param string $authCode 授权码
     * @param int $roleId 角色编号
     * @param string|null $name 角色名称
     * @param string|null $description 角色描述
     * @param bool|null $isEnable 是否可用
     * @param array|null $perms 权限列表
     * @return array|bool 成功则返回最新的角色信息，失败返回false
     * @throws Exception
     */
    public function updatePlatformRole(string $authCode, int $roleId, ?string $name=null, ?string $description=null,
                                       ?bool $isEnable=null, ?array $perms=null)
    {
        return $this->roleRepo->updatePlatformRole($roleId, $name, $description, $isEnable, $perms);
    }

    /**
     * 更新平台角色
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @param string|null $name 角色名称
     * @param string|null $description 角色描述
     * @param bool|null $isEnable 是否可用
     * @param array|null $perms 权限集合
     * @return array|bool 成功则返回最新的角色信息，失败返回false
     * @throws Exception
     */
    public function updateAppRole(string $authCode, int $appId, int $roleId, ?string $name=null,
                                  ?string $description=null, ?bool $isEnable=null, ?array $perms=null)
    {
        return $this->roleRepo->updateAppRole($appId, $roleId, $name, $description, $isEnable, $perms);
    }

    /**
     * 获取平台角色
     *
     * @param string $authCode 授权码
     * @param int $roleId 角色编号
     * @return array 角色信息
     */
    public function getPlatformRole(string $authCode, int $roleId)
    {
        return $this->roleRepo->getPlatformRole($roleId);
    }

    /**
     * 获取应用角色
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @return array 角色信息
     */
    public function getAppRole(string $authCode, int $appId, int $roleId)
    {
        return $this->roleRepo->getAppRole($appId, $roleId);
    }

    /**
     * 删除平台角色
     *
     * @param string $authCode 授权码
     * @param int $roleId 角色编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deletePlatformRole(string $authCode, int $roleId)
    {
        $result = $this->roleLogic->deletePlatformRole($roleId);

        return $result;
    }

    /**
     * 删除应用角色
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteAppRole(string $authCode, int $appId, int $roleId)
    {
        $result = $this->roleLogic->deleteAppRole($appId, $roleId);

        return $result;
    }


    /**
     * 添加一个用户到角色
     *
     * @param string $authCode 授权编号
     * @param int $roleId 角色编号
     * @param int $userId 用户编号
     * @return mixed
     */
    public function addUserToRole(string $authCode, int $roleId, int $userId)
    {
        return $this->roleRepo->bindRole2User($roleId, $userId);
    }

    /**
     * 把一个用户一处某个角色
     *
     * @param string $authCode 授权码
     * @param int $roleId 角色编号
     * @param int $userId 用户编号
     * @return bool 是否成功
     *
     * @throws \Exception
     */
    public function deleteUserFromRole(string $authCode, int $roleId, int $userId)
    {
        $result =  $this->roleRepo->unbindRole2User($roleId, $userId);

        return $result !== false ? true : false;
    }

    /**
     * 获取应用角色
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $userId 角色编号
     * @return array 角色信息
     */
    public function getAppPermsOfUser(string $authCode, int $userId)
    {
        $perms = [];
        $roles = $this->roleRepo->getAppRolesOfUser($userId, ['state'=>true]);
        foreach ($roles as $role){
            $perms = array_merge($perms, $role['perms']);
        }

        return $perms;
    }

    /**
     * 获取应用角色
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $userId 角色编号
     * @return array 角色信息
     */
    public function getAppPermsOfAppUser(string $authCode, int $appId, int $userId)
    {
        $perms = [];
        $roles = $this->roleRepo->getAppRolesOfAppUser($appId, $userId, ['state'=>true]);
        foreach ($roles as $role){
            $perms = array_merge($perms, $role['perms']);
        }

        return $perms;
    }

    /**
     * 获取平台用户角色权限
     *
     * @param string $authCode
     * @param int $userId
     * @return array
     */
    public function getPlatformPermsOfUser(string $authCode, int $userId)
    {
        $perms = [];
        $roles = $this->roleRepo->getPlatformRolesOfUser($userId, ['state'=>true], ['perms']);
        foreach ($roles as $role){
            $perms = array_merge($perms, $role['perms']);
        }

        return $perms;
    }

}