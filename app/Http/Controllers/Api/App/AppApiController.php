<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\Controller;
use App\Http\Libraries\AuthUser;
use App\Http\Requests\PostAppRequest;
use App\Http\Requests\PutAppRequest;
use App\Services\App\Service\AppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * 应用相关接口
 *
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class AppApiController extends Controller
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * AppApiController constructor.
     */
    public function __construct()
    {
        $this->appService = AppService::instance();
    }

    /**
     * 创建平台角色
     *
     * @param PostAppRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeApp(PostAppRequest $request)
    {
        try{
            $authUser = $this->getAuthUser();

            $data = $request->only(['name', 'userId', 'description', 'state']);
            $data['state'] = !empty($data['state']) ? true : false;

            $userId = $authUser->getAuthIdentifier();
            if($authUser->checkIsPlatformUser() && $data['userId'] > 0){
                $userId = (int) $data['userId'];
            }

            $role = $this->appService->addApp('123', $userId, $data['name'], $data['description'],
                $data['state']);

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
     * @param PutAppRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateApp(PutAppRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $data = $request->only(['name', 'userId', 'description', 'state']);
            $data['state'] = !empty($data['state']) ? true : false;

            $authUser = $this->getAuthUser();

            $userId = null;
            if($authUser->checkIsPlatformUser() && $data['userId'] > 0){
                $userId = (int) $data['userId'];
            }

            $app = $this->appService->updateApp('123', $appId, $userId, $data['name'], $data['description'],
                $data['state']);

            if(! $app){
                throw new \Exception('更新应用失败！');
            }

            return $this->successResponse();

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除应用
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyApp()
    {
        try{
            $appId = Route::input('appId');

            $result = $this->appService->deleteApp('123', $appId);

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
     * 获取授权用户
     *
     * @return AuthUser|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getAuthUser()
    {
        return Auth::user();
    }
}
