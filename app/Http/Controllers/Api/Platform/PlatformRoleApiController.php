<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\PostRoleRequest;
use App\Http\Requests\PostRoleUserRequest;
use App\Http\Requests\PutRoleRequest;
use App\Services\User\Service\RoleService;
use Illuminate\Support\Facades\Route;

/**
 * 角色相关接口
 *
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class PlatformRoleApiController extends Controller
{
    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * PlatformRoleApiController constructor.
     */
    public function __construct()
    {
        $this->roleService = RoleService::instance();
    }

    /**
     * 创建平台角色
     *
     * @param PostRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePlatformRole(PostRoleRequest $request)
    {
        try{
            $data = $request->only(['name', 'description', 'state', 'perms']);

            $data['state'] = !empty($data['state']) ? true : false;

            $role = $this->roleService->addPlatformRole('123', $data['name'], $data['description'],
                $data['state'], $data['perms']);

            if(! $role){
                throw new \Exception('创建角色失败！');
            }

        }catch (\Exception $e){
            $this->exceptionResponse($e);
        }

        return $this->successResponse();
    }

    /**
     * 更新平台角色
     *
     * @param PutRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePlatformRole(PutRoleRequest $request)
    {
        try{
            $data = $request->only(['id', 'name', 'description', 'state', 'perms']);

            $data['state'] = !empty($data['state']) ? true : false;

            $role = $this->roleService->updatePlatformRole('123', $data['id'], $data['name'], $data['description'],
                $data['state'], $data['perms']);

            if(! $role){
                throw new \Exception('创建角色失败！');
            }


            return $this->successResponse();

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除平台角色
     *
     * @param DeleteRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPlatformRole(DeleteRoleRequest $request)
    {
        try{
            $roleId = $request->input('id');

            $result = $this->roleService->deletePlatformRole('123', $roleId);

            if($result){
                return $this->successResponse();

            }else{
                return $this->failResponse('删除失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 添加角色用户
     *
     * @param PostRoleUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePlatformRoleUser(PostRoleUserRequest $request)
    {
        try{
            $roleId = Route::input('roleId');
            $userId = $request->input('userId');

            $result = $this->roleService->addUserToRole('123', $roleId, $userId);

            if($result){
                return $this->successResponse($result);
            }else{
                return $this->failResponse('添加失败!');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 移除角色用户
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPlatformRoleUser()
    {
        try{
            $roleId = Route::input('roleId');
            $userId = Route::input('userId');

            $result = $this->roleService->deleteUserFromRole('123', $roleId, $userId);

            if($result){
                return $this->successResponse();
            }else{
                return $this->failResponse('删除失败!');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
