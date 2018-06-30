<?php

namespace App\Services\User\Logic;

use App\Services\_Base\Exception;
use App\Services\_Base\Logic;
use App\Services\User\Repository\RoleRepository;
use App\Services\User\Repository\UserRepository;
use Illuminate\Support\Facades\DB;

class UserLogic extends Logic
{
    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @var RoleRepository
     */
    protected $roleRepo;

    protected function __construct()
    {
        $this->userRepo = UserRepository::instance();
        $this->roleRepo = RoleRepository::instance();
    }

    /**
     * 删除平台用户
     *
     * 需要先验证用户创建的应用是否删除（服务层），如果应用都删除，则执行删除用户逻辑
     * 此处假设服务层都验证通过了
     *
     * @param int $userId 用户编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deletePlatformUser(int $userId)
    {
        try{
            DB::beginTransaction();

            if($userId == $this->userRepo->getSuperAdminId()){
                throw new Exception('不能删除平台超管！');
            }

            // 判断用户类型
            $user = $this->userRepo->getUser($userId);
            if(! $user){
                throw new Exception('未找到对应的用户！');
            }

            if($user['userType'] !== $this->userRepo->getPlatformUserType()){
                throw new Exception('错误的用户类型！');
            }

            // 解绑用户和平台角色 (没问题哈！)
            $result = $this->roleRepo->unbindAllUser2RoleByUser($userId);
            if($result === false){
                throw new Exception('取消角色与用户关联失败！');
            }

            // 删除用户
            $result = $this->userRepo->deleteUser($userId);
            if($result === false){
                throw new Exception('删除用户失败！');
            }

            DB::commit();

            return true;

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 删除应用用户（含：应用开发者、应用管理员、应用运营员）
     *
     * @param int $userId
     * @return bool
     * @throws \Exception
     */
    public function deleteAppUser(int $userId)
    {
        try{
            DB::beginTransaction();

            // 判断用户类型
            $user = $this->userRepo->getUser($userId);
            if(! $user){
                throw new Exception('用户不存在！');
            }
            if(! in_array($user['userType'], array(
                $this->userRepo->getAppDeveloperUserType(),
                $this->userRepo->getAppManagerUserType(),
                $this->userRepo->getAppContentUserType(),
            ))){
                throw new Exception('该用户不是应用用户！');
            }

            if($user['userType'] === $this->userRepo->getAppContentUserType()){
                // 解绑用户和应用角色
                $result = $this->roleRepo->unbindAllUser2RoleByUser($userId);
                if($result === false){
                    throw new Exception('取消角色与用户关联失败！');
                }
            }

            // 删除用户
            $result = $this->userRepo->deleteUser($userId);
            if($result === false){
                throw new Exception('删除用户失败！');
            }

            DB::commit();

            return true;

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}