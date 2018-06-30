<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Libraries\AuthUser;
use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class AppController extends Controller
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
     * @var AppService
     */
    protected $appService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->roleService = RoleService::instance();
        $this->userService = UserService::instance();
        $this->appService = AppService::instance();
    }

    /**
     * 应用列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexApps(Request $request)
    {
        $authUser = $this->getAuthUser();

        $query = [
            'name' => $request->query('name', null),
            'state' => $request->query('state', null),
        ];
        if(! $authUser->checkIsPlatformUser()){
            $query['userId'] = $authUser->getAuthIdentifier();
        }


        $apps = $this->appService->getAppsByCondition('123', $query);
        if(! empty($apps)){
            $userIds = [];
            foreach ($apps as $app){
                $userIds[] = $app['userId'];
            }

            $users = $this->userService->getUsersByIds('123', $userIds);
            $usernameMap = [];
            foreach ($users as $user){
                $usernameMap[$user['id']] = $user['username'];
            }
            foreach ($apps as $key => $app){
                $apps[$key]['username'] = $usernameMap[$app['userId']];
            }
        }

        return view('platform.app.indexApps', [
            'query'     => $query,
            'apps'      => $apps
        ]);
    }

    /**
     * 创建应用表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createApp()
    {
        try{
            $authUser = $this->getAuthUser();
            $users = $this->userService->getUsers('123', ['state'=>true]);

            return view('platform.app.createApp', [
                'users' => $users,
                'userId' => $authUser->getAuthIdentifier()
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑应用表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editApp()
    {
        try{
            $appId = Route::input('appId');

            $users = $this->userService->getUsers('123', ['state'=>true]);
            $app = $this->appService->getApp('123', $appId);

            return view('platform.app.editApp', [
                'app' => $app,
                'users' => $users,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 应用主页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manageApp()
    {
        try{
            $appId = Route::input('appId');

            $app = $this->appService->getApp('123', $appId);

            OperationContext::setAppId($appId);

            return view('platform.app.manageApp', [
                'app' => $app
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * @return AuthUser|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getAuthUser()
    {
        return Auth::user();
    }
}
