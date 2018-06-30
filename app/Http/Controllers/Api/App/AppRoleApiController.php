<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\PostAppRoleRequest;
use App\Http\Requests\PostAppRoleUserRequest;
use App\Http\Requests\PutAppRoleRequest;
use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Support\Facades\Route;

/**
 * 应用角色相关接口
 *
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class AppRoleApiController extends Controller
{

    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * AppRoleApiController constructor.
     */
    public function __construct()
    {
        $this->roleService = RoleService::instance();

        $this->userService = UserService::instance();
    }

    /**
     * 删除应用角色
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAppRole()
    {
        try{
            $appId = Route::input('appId');
            $roleId = Route::input('roleId');

            $result = $this->roleService->deleteAppRole('123', $appId, $roleId);

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
     * 创建应用角色
     *
     * @param PostAppRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAppRole(PostAppRoleRequest $request)
    {
        try{
            $appId = Route::input('appId');

            $data = $request->only(['name', 'description', 'state', 'perms']);
            $data['state'] = !empty($data['state']) ? true : false;

            $appRole = $this->roleService->addAppRole('123', $appId, $data['name'], $data['description'],
                $data['state'], $data['perms']);

            if($appRole){
                return $this->successResponse($appRole);
            }else{
                return $this->failResponse('新增失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新应用角色
     *
     * @param PutAppRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAppRole(PutAppRoleRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $roleId = Route::input('roleId');

            $data = $request->only(['name', 'description', 'state', 'perms']);
            $data['state'] = !empty($data['state']) ? true : false;

            $appRole = $this->roleService->updateAppRole('123', $appId, $roleId, $data['name'],
                $data['description'], $data['state'], $data['perms']);

            if($appRole){
                return $this->successResponse($appRole);

            }else{
                return $this->failResponse('新增失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 添加用户到角色
     *
     * @param PostAppRoleUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAppRoleUser(PostAppRoleUserRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $roleId = Route::input('roleId');
            $userId = $request->input('userId');

            $result = $this->roleService->addUserToRole('123', $roleId, $userId);

            if($result){
                return $this->successResponse();
            }else{
                return $this->failResponse('操作失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 把用户从角色移除
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAppRoleUser()
    {
        try{
            $appId = Route::input('appId');
            $roleId = Route::input('roleId');
            $userId = Route::input('userId');

            $result = $this->roleService->deleteUserFromRole('123', $roleId, $userId);

            if($result){
                return $this->successResponse();
            }else{
                return $this->failResponse('操作失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
