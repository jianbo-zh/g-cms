<?php

namespace App\Services\User\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\User\Model\UserModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

/**
 * 获取用户信息相关仓库
 *
 * Class UserRepository
 * @package App\Services\User\Repository
 */
class UserRepository extends Repository
{

    /**
     * 添加一个用户
     *
     * @param string $userType 用户类型 [platform, normal, app]
     * @param int $appId 应用编号
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $avatar 头像
     * @param string $phone 手机号
     * @param string $email 邮箱
     * @param bool $isEnable 是否启用
     * @param string $password 登录密码
     * @return array
     * @throws Exception
     */
    public function addUser(string $userType, int $appId, string $username, string $nickname,
                            string $avatar, string $phone, string $email, bool $isEnable, string $password)
    {
        if(! in_array($userType, array_keys(UserModel::getUserTypeMap()))){
            throw new Exception('错误的用类型！');
        }
        $user = UserModel::create([
            'user_type'         => $userType,
            'app_id'            => $appId ? : null,
            'username'          => $username,
            'nickname'          => $nickname,
            'avatar'            => $avatar,
            'phone'             => $phone,
            'email'             => $email,
            'state'             => $isEnable ? UserModel::STATE_ENABLE : UserModel::STATE_DISABLE,
            'password'          => $password,
            'api_token'         => $this->genApiTokenString()
        ]);

        return $this->normalizeReturn($user);
    }

    /**
     * 更新用户信息
     *
     * @param int $userId 用户编号
     * @param string|null $userType 用户类型
     * @param string|null $nickname 昵称
     * @param string|null $phone 手机号
     * @param string|null $email 邮箱
     * @param bool|null $isEnable 是否启用
     * @return bool|array 成功则返回更新后的用户，失败返回false
     * @throws Exception
     */
    public function updateUser(int $userId, ?string $nickname=null, ?string $phone=null, ?string $email=null,
                               ?bool $isEnable=null)
    {
        if(is_null($nickname) && is_null($phone) && is_null($email) && is_null($isEnable)){
            throw new Exception('更新参数不能都为空！');
        }

        $user = UserModel::findOrFail($userId);
        if(!is_null($nickname)){
            $user->nickname = $nickname;
        }
        if(!is_null($phone)){
            $user->phone = $phone;
        }
        if(!is_null($email)){
            $user->email = $email;
        }
        if(!is_null($isEnable)){
            $user->state = $isEnable ? UserModel::STATE_ENABLE : UserModel::STATE_DISABLE;
        }

        $result = $user->save();

        return $result ? $this->normalizeReturn($user) : false;
    }

    /**
     * 更新用户密码
     *
     * @param int $userId 用户编号
     * @param string $password 新密码
     * @return bool|array 成功返回用户信息，失败返回false
     */
    public function updateUserPassword(int $userId, string $password)
    {
        $user = UserModel::findOrFail($userId);
        $user->password = $password;

        $result = $user->save();

        return $result ? $this->normalizeReturn($user) : false;
    }

    /**
     * 更新用户头像
     *
     * @param int $userId 用户编号
     * @param string $avatar 头像
     * @return array|bool 成功返回用户信息，失败返回false
     */
    public function updateUserAvatar(int $userId, string $avatar)
    {
        $user = UserModel::findOrFail($userId);
        $user->avatar = $avatar;

        $result = $user->save();

        return $result ? $this->normalizeReturn($user) : false;
    }

    /**
     * 更新用户记住我TOKEN
     *
     * @param int $userId 用户编号
     * @param string $rememberToken Token
     * @return bool 是否成功
     */
    public function updateUserRememberToken(int $userId, string $rememberToken)
    {
        $user = UserModel::findOrFail($userId);

        $user->remember_token = $rememberToken;

        $result = $user->save();

        return $result;
    }

    /**
     * 获取用户信息
     *
     * @param int $userId 用户编号
     * @param array|null $fields 返回字段
     * @return array 用户信息
     */
    public function getUser(int $userId, array $fields=null)
    {
        $user = UserModel::when(!empty($fields), function(Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->find($userId);

        return $this->normalizeReturn($user);
    }

    /**
     * 根据用户字段信息获取用户
     *
     * @param array $credentials 用户字段信息
     * @return mixed|null 用户信息
     */
    public function getUserByCredentials(array $credentials)
    {
        $query = UserModel::query();
        foreach ($credentials as $key => $val){
            $query->where(snake_case($key), $val);
        }

        $user = $query->first();

        return $this->normalizeReturn($user);
    }

    /**
     * 获取平台的所有用户的编号
     *
     * @return array 用户编号
     */
    public function getPlatformUserIds()
    {
        $result = UserModel::where('user_type', UserModel::USER_TYPE_PLATFORM)
            ->select('id')
            ->get();

        $userIds = [];

        foreach ($result as $value){
            $userIds[] = $value['id'];
        }

        return $userIds;
    }

    /**
     * 获取所有应用用户的编号
     *
     * @param int $appId 应用编号
     * @return array 用户编号
     */
    public function getUserIdsOfApp(int $appId)
    {
        $userIds = [];
        $users = UserModel::where('user_type', UserModel::USER_TYPE_APP_CONTENT)
            ->where('app_id', $appId)
            ->select('id')
            ->get();

        foreach ($users as $user){
            $userIds[] = $user['id'];
        }

        return $userIds;
    }

    /**
     * 通过用户编号集获取用户集
     *
     * @param array $userIds 用户编号集
     * @param array|null $fields 返回字段
     * @return mixed 用户集
     */
    public function getUsersByUserIds(array $userIds, array $fields=null)
    {
        $users = UserModel::whereIn('id', $userIds)
            ->when(!is_null($fields), function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->get();

        return $this->normalizeReturn($users);
    }

    /**
     * 获取平台用户集合，通过调节搜索
     *
     * @param array|null $condition 查询条件 [username, state]
     * @param array|null $fields 返回字段
     * @param int $offset 返回偏移量
     * @param int|null $limit 返回数量
     * @return array 用户集
     */
    public function getUsers(?array $condition=null, ?array $fields=null, int $offset=0, ?int $limit=null)
    {
        $users = UserModel::when(!empty($condition), function (Builder $query) use ($condition){
                if(!empty($condition['username'])){
                    $query->where('username', $condition['username']);
                }
                if(!empty($condition['userType'])){
                    $query->where('user_type', $condition['userType']);
                }
                if(isset($condition['state'])){
                    $query->where('state', !empty($condition['state']) ? UserModel::STATE_ENABLE :
                        UserModel::STATE_DISABLE);
                }
                return $query;
            })
            ->when($fields, function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->when($limit > 0, function (Builder $query) use ($offset, $limit){
                return $query->offset($offset)->limit($limit);
            })
            ->get();

        return $this->normalizeReturn($users);
    }

    /**
     * 获取平台用户数量
     *
     * @param int $appId 应用编号
     * @param array|null $condition 查询条件
     * @param array|null $fields 返回字段
     * @param int $offset 返回偏移量
     * @param int|null $limit 返回数量
     * @return array 用户集
     */
    public function getUsersOfApp(int $appId, ?array $condition=null, ?array $fields=null, int $offset=0,
                                  ?int $limit=null)
    {
        $users = UserModel::where('user_type', UserModel::USER_TYPE_APP_CONTENT)
            ->where('app_id', $appId)
            ->when($condition, function (Builder $query) use ($condition){
                if(!empty($condition['username'])){
                    $query->where('username', $condition['username']);
                }
                if(isset($condition['state'])){
                    $query->where('state', !empty($condition['state']) ? UserModel::STATE_ENABLE :
                        UserModel::STATE_DISABLE);
                }
                return $query;
            })
            ->when($fields, function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->when($limit > 0, function (Builder $query) use ($offset, $limit){
                return $query->offset($offset)->limit($limit);
            })
            ->get();

        return $this->normalizeReturn($users);
    }

    /**
     * 删除用户
     *
     * @param int $userId 用户编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteUser(int $userId)
    {
        $user = UserModel::findOrFail($userId);

        return $user->delete();
    }

    /**
     * 获取超级用户编号
     *
     * @return int
     */
    public function getSuperAdminId()
    {
        return UserModel::SUPER_USER_ID;
    }

    /**
     * 获取平台用户类型字符串
     *
     * @return string
     */
    public function getPlatformUserType()
    {
        return UserModel::USER_TYPE_PLATFORM;
    }

    /**
     * 获取应用开发者用户类型字符串
     *
     * @return string
     */
    public function getAppDeveloperUserType()
    {
        return UserModel::USER_TYPE_APP_DEVELOPER;
    }

    /**
     * 获取应用管理员用户类型字符串
     *
     * @return string
     */
    public function getAppManagerUserType()
    {
        return UserModel::USER_TYPE_APP_MANAGER;
    }

    /**
     * 获取应用运营员用户类型字符串
     *
     * @return string
     */
    public function getAppContentUserType()
    {
        return UserModel::USER_TYPE_APP_CONTENT;
    }

    /**
     * 获取所有的用户类型
     *
     * @return array
     */
    public function getUserTypes()
    {
        return UserModel::getUserTypeMap();
    }

    /**
     * 生成ApiToken字符串
     *
     * @return string
     */
    public function genApiTokenString()
    {
        return str_random(20);
    }

    /**
     *  更新用户的ApiToken
     *
     * @param int $userId 用户编号
     * @return bool|string
     * @throws Exception
     */
    public function updateUserApiToken(int $userId)
    {
        $user = UserModel::find($userId);
        if(! $user){
            throw new Exception('未找到该用户！');
        }
        $user->api_token = $this->genApiTokenString();

        $result = $user->save();

        return $result ? $user->api_token : false;
    }
}