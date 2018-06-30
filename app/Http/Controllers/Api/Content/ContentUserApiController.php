<?php

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\PostAppUserRequest;
use App\Http\Requests\PostUserRequest;
use App\Http\Requests\PutAppUserRequest;
use App\Services\User\Service\UserService;
use Illuminate\Support\Facades\Route;

/**
 * 用户相关接口
 *
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class ContentUserApiController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * ContentUserApiController constructor.
     */
    public function __construct()
    {
        $this->userService = UserService::instance();
    }

    /**
     * 创建应用用户
     *
     * @param PostAppUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeContentUser(PostAppUserRequest $request)
    {
        try{
            $appId = Route::input('appId');

            $data = $request->only(['username', 'nickname', 'phone', 'email', 'state', 'password']);
            $data['state'] = !empty($data['state']) ? true : false;

            $appContentUser = $this->userService->addAppContentUser('123', $appId, $data['username'],
                $data['nickname'], '', $data['phone'], $data['email'], $data['state'], $data['password']);

            if($appContentUser){
                return $this->successResponse($appContentUser);

            }else{
                return $this->failResponse('新增用户失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新应用用户
     *
     * @param PutAppUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateContentUser(PutAppUserRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $userId = Route::input('userId');

            $data = $request->only(['nickname', 'phone', 'email', 'state', 'password']);
            $data['state'] = !empty($data['state']) ? true : false;

            $user = $this->userService->updateUser('123', $userId, $data['nickname'], $data['phone'],
                $data['email'], $data['state']);

            if(! $user){
                throw new \Exception('更新用户信息失败！');
            }
            if($data['password']){
                $user = $this->userService->updateUserPassword('123', $userId, $data['password']);
                if(! $user){
                    throw new \Exception('更新用户密码失败！');
                }
            }

            return $this->successResponse($user);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除应用用户
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyContentUser()
    {
        try{
            $appId = Route::input('appId');
            $userId = Route::input('userId');

            $result = $this->userService->deleteAppUser('123', $appId, $userId);

            if($result){
                return $this->successResponse();

            }else{
                return $this->failResponse('删除失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
