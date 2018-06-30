<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Api\Controller;
use App\Http\Libraries\AuthUser;
use App\Http\Requests\PatchProfileRequest;
use App\Http\Requests\PostUserRequest;
use App\Http\Requests\PutUserRequest;
use App\Services\User\Service\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * 用户相关接口
 *
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class PlatformUserApiController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * PlatformUserApiController constructor.
     */
    public function __construct()
    {
        $this->userService = UserService::instance();
    }

    /**
     * 新增平台用户
     *
     * @param PostUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePlatformUser(PostUserRequest $request)
    {
        try{
            $data = $request->only(['userType', 'username', 'nickname', 'phone', 'email', 'state', 'password']);
            $data['state'] = !empty($data['state']) ? true : false;

            $userService = UserService::instance();
            if($data['userType'] === 'platform'){
                $user = $userService->addPlatformUser('123', $data['username'], $data['nickname'], '',
                    $data['phone'], $data['email'], $data['state'], $data['password']);

            }else{
                $user = $userService->addAppSuperUser('123', $data['userType'], $data['username'],
                    $data['nickname'], '', $data['phone'], $data['email'], $data['state'], $data['password']);
            }

            if(! $user){
                throw new \Exception('创建平台用户失败！');
            }

            return $this->successResponse($user);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新平台用户
     *
     * @param PutUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePlatformUser(PutUserRequest $request)
    {
        try{
            $data = $request->only(['nickname', 'phone', 'email', 'state', 'password']);
            $data['state'] = !empty($data['state']) ? true : false;

            $userId = Route::input('userId');


            $userService = UserService::instance();

            $user = $userService->updateUser('123', $userId, $data['nickname'], $data['phone'],
                $data['email'], $data['state']);

            if(! $user){
                throw new \Exception('创建平台用户失败！');
            }

            if(!empty($data['password'])){
                $user = $userService->updateUserPassword('123', $userId, $data['password']);

                if(! $user){
                    throw new \Exception('更新其他信息成功，但更新密码失败！');
                }
            }

            return $this->successResponse($user);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新个人信息
     *
     * @param PatchProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateProfile(PatchProfileRequest $request)
    {
        try{
            $params = $request->only(['nickname', 'phone', 'email', 'password']);

            $userService =UserService::instance();

            $userId = $request->user()->id;

            $user = $userService->updateUser('123', $userId, $params['nickname'], $params['phone'],
                $params['email']);

            if(! $user){
                throw new \Exception('更新用户信息失败！');
            }
            if($params['password']){
                $user = $userService->updateUserPassword('123', $userId, $params['password']);
                if(! $user){
                    throw new \Exception('更新密码失败！');
                }
            }

            // 更新当前用户的会话信息
            Auth::setUser(new AuthUser($user));

            return $this->successResponse();

        }catch (\Exception $e){

            return $this->failResponse($e);
        }
    }

    /**
     * 删除平台用户
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPlatformUser()
    {
        try{
            $userId = Route::input('userId');

            $userService = UserService::instance();

            $result = $userService->deletePlatformUser('123', $userId);

            if($result){
                return $this->successResponse();

            }else{
                return $this->failResponse('删除用户失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
