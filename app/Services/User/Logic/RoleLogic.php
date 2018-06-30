<?php

namespace App\Services\User\Logic;

use App\Services\_Base\Exception;
use App\Services\_Base\Logic;
use App\Services\User\Repository\RoleRepository;
use Illuminate\Support\Facades\DB;

class RoleLogic extends Logic
{
    /**
     * @var RoleRepository
     */
    protected $roleRepo;

    protected function __construct()
    {
        $this->roleRepo = RoleRepository::instance();
    }

    /**
     * 删除平台角色
     *
     * @param int $roleId 角色编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deletePlatformRole(int $roleId)
    {
        try{
            DB::beginTransaction();

            // 清空角色下面的用户
            $result = $this->roleRepo->unbindAllRole2UserByRole($roleId);

            if($result === false){
                throw new Exception('取消角色与用户关联失败！');
            }

            // 删除角色
            $result = $this->roleRepo->deletePlatformRole($roleId);
            if($result === false){
                throw new Exception('删除角色失败！');
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return true;
    }



    /**
     * 删除应用角色
     *
     * @param int $appId 应用编号
     * @param int $roleId 角色编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteAppRole(int $appId, int $roleId)
    {
        try{
            DB::beginTransaction();

            // 清空角色下面的用户
            $result = $this->roleRepo->unbindAllRole2UserByRole($roleId);

            if($result === false){
                throw new Exception('取消角色与用户关联失败！');
            }

            // 删除角色
            $result = $this->roleRepo->deleteAppRole($appId, $roleId);
            if($result === false){
                throw new Exception('删除角色失败！');
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return true;
    }
}