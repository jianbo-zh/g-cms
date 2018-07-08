<?php

namespace App\Services\User\Service;

use App\Services\_Base\Exception;
use App\Services\_Base\Service;
use App\Services\App\Service\AppService;
use App\Services\User\Logic\RoleLogic;
use App\Services\User\Logic\UserLogic;
use App\Services\User\Repository\RoleRepository;
use App\Services\User\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * 用户、角色、权限服务
 *
 * Class UserRoleAuthService
 * @package App\Services\User\Service
 */
class UserService extends Service
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
     * @var UserLogic
     */
    protected $userLogic;

    protected function __construct()
    {
        $this->userRepo = UserRepository::instance();

        $this->roleRepo = RoleRepository::instance();

        $this->roleLogic = RoleLogic::instance();

        $this->userLogic = UserLogic::instance();

    }

    /**
     * 添加一个平台用户
     *
     * @param string $authCode 授权码
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $avatar 头像
     * @param string $phone 手机号
     * @param string $email 邮箱
     * @param bool $isEnable 是否启用
     * @param string $password 登录密码
     * @return array 用户信息
     * @throws Exception
     */
    public function addPlatformUser(string $authCode, string $username, string $nickname, string $avatar,
                                    string $phone, string $email, bool $isEnable, string $password)
    {
        $password = $this->commonHashUserPassword($password);

        $user = $this->userRepo->addUser($this->userRepo->getPlatformUserType(), 0, $username,
            $nickname, $avatar, $phone, $email, $isEnable, $password);

        return $user;
    }

    /**
     * 添加一个应用用户
     *
     * @param string $authCode 授权码
     * @param string $appUserType 应用编号
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $avatar 头像
     * @param string $phone 手机号
     * @param string $email 邮箱
     * @param bool $isEnable 是否启用
     * @param string $password 登录密码
     * @return array 用户信息
     * @throws \Exception
     */
    public function addAppSuperUser(string $authCode, string $appUserType, string $username,
                                             string $nickname, string $avatar, string $phone, string $email,
                                             bool $isEnable, string $password)
    {
        $password = $this->commonHashUserPassword($password);

        if(! in_array($appUserType, array('app_developer', 'app_manager'))){
            throw new Exception('错误的用户类型！');
        }

        $user = $this->userRepo->addUser($appUserType, 0, $username, $nickname, $avatar, $phone, $email,
            $isEnable, $password);


        return $user;
    }


    /**
     * 添加一个应用用户
     *
     * @param string $authCode 授权码
     * @param string $appUserType 应用编号
     * @param int $appId 应用编号
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $avatar 头像
     * @param string $phone 手机号
     * @param string $email 邮箱
     * @param bool $isEnable 是否启用
     * @param string $password 登录密码
     * @return array 用户信息
     * @throws \Exception
     */
    public function addAppContentUser(string $authCode, int $appId, string $username, string $nickname, string $avatar,
                               string $phone, string $email, bool $isEnable, string $password)
    {
        $password = $this->commonHashUserPassword($password);

        $user = $this->userRepo->addUser($this->userRepo->getAppContentUserType(), $appId, $username, $nickname,
            $avatar, $phone, $email, $isEnable, $password);


        return $user;
    }

    /**
     * 检查用户原始密码与存储密码是否一致
     *
     * @param string $authCode 授权码
     * @param string $originalPassword 原始密码
     * @param string $hashPassword hash后存储的密码
     * @return bool 是否一致
     */
    public function checkUserPassword(string $authCode, string $originalPassword, string $hashPassword)
    {
        return $this->commonHashUserPasswordCheck($originalPassword, $hashPassword);
    }

    /**
     * 更新用户信息
     *
     * @param string $authCode 用户编号
     * @param int $userId 用户编号
     * @param string|null $nickname 昵称
     * @param string|null $phone 手机号
     * @param string|null $email 邮箱
     * @param bool|null $isEnable 是否启用
     * @return bool|array 成功则返回更新后的用户，失败返回false
     * @throws \App\Services\_Base\Exception
     */
    public function updateUser(string $authCode, int $userId, ?string $nickname=null, ?string $phone=null,
                               ?string $email=null, ?bool $isEnable=null)
    {
        $user = $this->userRepo->updateUser($userId, $nickname, $phone, $email, $isEnable);

        return $user;
    }

    /**
     * 更新用密码
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @param string $password 新密码
     * @return array|bool
     */
    public function updateUserPassword(string $authCode, int $userId, string $password)
    {
        $password = $this->commonHashUserPassword($password);

        $user = $this->userRepo->updateUserPassword($userId, $password);

        return $user;
    }

    /**
     * 更新用户头像
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @param string $avatar 头像
     * @return array|bool 是否成功
     */
    public function updateUserAvatar(string $authCode, int $userId, string $avatar)
    {
        $user = $this->userRepo->updateUserAvatar($userId, $avatar);

        return $user;
    }

    /**
     * 更新用户记住我TOKEN
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @param string $token 更新Token
     * @return bool 是否成功
     */
    public function updateUserRememberToken(string $authCode, int $userId, string $token)
    {
        $result = $this->userRepo->updateUserRememberToken($userId, $token);

        return $result;
    }

    /**
     * 获取用户信息
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @param array $fields 用户编号
     * @return array 用户信息
     */
    public function getUser(string $authCode, int $userId, array $fields=null)
    {
        if(! is_null($fields)){
            $user = $this->userRepo->getUser($userId, $fields);
        }else{
            $user = $this->userRepo->getUser($userId);
        }

        return $user;
    }

    /**
     * 通过用户凭证获取用户信息
     *
     * @param string $authCode 授权码
     * @param array $credentials 用户凭证
     * @return array 用户信息
     * @throws Exception
     */
    public function getUserByCredentials(string $authCode, array $credentials)
    {
        if(isset($credentials['password'])){
            unset($credentials['password']);
        }

        if(empty($credentials)){
            throw new Exception('用户凭证参数错误！');
        }

        $user = $this->userRepo->getUserByCredentials($credentials);

        return $user;
    }

    /**
     * 删除应用用户
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $userId 用户编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteAppUser(string $authCode, int $appId, int $userId)
    {
        $result = $this->userLogic->deleteAppUser($userId);

        return $result;
    }

    /**
     * 获取属于某个角色的用户集
     *
     * @param string $authCode 授权码
     * @param int $roleId 角色编号
     * @param array $fields 返回字段 [id, username, nickname, avatar, email, phone, state]
     * @param int $offset 偏移数值
     * @param int $limit 偏移数量
     * @return array 用户集
     */
    public function getUsersBelongToRole(string $authCode, int $roleId, ?array $fields=null, int $offset=0, int $limit=20)
    {
        $users = $this->roleRepo->getUsersBelongToRole($roleId, $fields, $offset, $limit);

        return $users;
    }

    /**
     * 获取不属于角色的平台用户
     *
     * @param string $authCode 授权码
     * @param int $roleId 角色编号
     * @return array 用户集合
     */
    public function getPlatformUsersNotBelongToRole(string $authCode, int $roleId)
    {
        $allUserIds = $this->userRepo->getPlatformUserIds();

        $roleUserIds = $this->roleRepo->getUserIdsBelongToRole($roleId);

        $noBelongUserIds = array_diff($allUserIds, $roleUserIds);

        $users = $this->userRepo->getUsersByUserIds($noBelongUserIds, ['id', 'username']);

        return $users;
    }

    /**
     * 获取不属于角色的应用用户
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @return array 用户集合
     */
    public function getAppUsersNotBelongToRole(string $authCode, int $appId, int $roleId)
    {
        $allAppUserIds = $this->userRepo->getUserIdsOfApp($appId);

        $roleUserIds = $this->roleRepo->getUserIdsBelongToRole($roleId);

        $noBelongUserIds = array_diff($allAppUserIds, $roleUserIds);

        $users = $this->userRepo->getUsersByUserIds($noBelongUserIds, ['id', 'username']);

        return $users;
    }

    /**
     * 获取平台用户
     *
     * @param string $authCode 授权码
     * @param array|null $condition 查询条件 [username, state]
     * @param int $offset 返回偏移量
     * @param int $limit 返回数量
     * @return array 用户集
     */
    public function getUsers(string $authCode, ?array $condition=null, int $offset=0, int $limit=20)
    {
        $users = $this->userRepo->getUsers($condition, null, $offset, $limit);

        return $users;
    }

    /**
     * 通过用户编号集获取用户集
     *
     * @param string $authCode 授权码
     * @param array $userIds 用户编号集
     * @return mixed
     */
    public function getUsersByIds(string $authCode, array $userIds)
    {
        $users = $this->userRepo->getUsersByUserIds($userIds);

        return $users;
    }

    /**
     * 获取App用户
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param array|null $condition 查询条件 [username, state]
     * @param int $offset 返回偏移量
     * @param int $limit 返回数量
     * @return array 用户集
     */
    public function getAppUsers(string $authCode, int $appId, ?array $condition=null, int $offset=0, ?int $limit=null)
    {
        $users = $this->userRepo->getUsersOfApp($appId, $condition, null, $offset, $limit);

        return $users;
    }

    /**
     * 删除平台用户
     *
     * @param string $authCode 授权码
     * @param int $userId 用户编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deletePlatformUser(string $authCode, int $userId)
    {
        $apps = $this->requestUserApps($userId);

        if(! empty($apps)){
            throw new Exception('当前用户存在应用，请先删除对应的应用！');
        }

        $result = $this->userLogic->deletePlatformUser($userId);

        return $result !== false ? true : false;
    }

    /**
     * 更新用户的ApiToken并返回
     *
     * @param string $authCode 授权码
     * @param $userId int 用户编号
     * @return bool|string
     * @throws Exception
     */
    public function updateUserApiToken(string $authCode, int $userId)
    {
        $apiToken = $this->userRepo->updateUserApiToken($userId);

        return $apiToken;
    }

    /**
     * 获取所有的用户类型
     *
     * @return array
     */
    public function getUserTypes()
    {
        return $this->userRepo->getUserTypes();
    }

    /**
     * 查询用户的应用集
     *
     * @param int $userId 用户
     * @return array 应用集
     */
    protected function requestUserApps(int $userId)
    {
        $appService = AppService::instance();

        $apps = $appService->getAppsOfUser('123', $userId);

        return $apps;
    }

    /**
     * 散列用户原始密码
     *
     * @param string $originalPassword 原始密码
     * @return string Hash后的密码
     */
    protected function commonHashUserPassword(string $originalPassword)
    {
        return Hash::make($originalPassword);
    }

    /**
     * 验证原始密码与Hash后的密码是否一致
     *
     * @param string $originalPassword 原始密码
     * @param string $hashPassword Hash后的密码
     * @return string Hash后的密码
     */
    protected function commonHashUserPasswordCheck(string $originalPassword, string $hashPassword)
    {
        return Hash::check($originalPassword, $hashPassword);
    }
}