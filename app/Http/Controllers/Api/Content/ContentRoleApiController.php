<?php

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\PostRoleUserRequest;
use App\Services\User\Service\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * 用户相关接口
 *
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class ContentRoleApiController extends Controller
{
    /**
     * @var RoleService
     */
    protected $roleService;

    public function __construct()
    {
        $this->roleService = RoleService::instance();
    }

    /**
     * 创建角色用户关联
     *
     * @param PostRoleUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeContentRoleUser(PostRoleUserRequest $request)
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
     * 删除角色用户关联
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyContentRoleUser()
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
