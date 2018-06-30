<?php

namespace App\Http\ViewComposers;

use App\Http\Libraries\AuthUser;
use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HeaderComposer
{
    /**
     * 头部导航视图组件
     *
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $authUser = $this->getAuthUser();

        $modules = [];

        if($authUser->checkIsPlatformUser()){

            $modules = $this->getPlatformUserHeader($authUser);

        }elseif($authUser->checkIsAppDeveloper()){

            $modules = $this->getAppDeveloperHeader($authUser);

        }elseif($authUser->checkIsAppManager()){

            $modules = $this->getAppManagerHeader($authUser);

        }elseif($authUser->checkIsAppContentUser()){

            $modules = $this->getAppContentUserHeader($authUser);
        }

        $view->with('user', $authUser->toArray());
        $view->with('modules', $modules);
    }

    /**
     * 获取当前授权用户
     *
     * @return AuthUser|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getAuthUser()
    {
        return  Auth::user();
    }

    /**
     * 获取平台用户的头部模块
     *
     * @param AuthUser $authUser 授权用户
     * @return array 模块列表
     * @throws \Exception
     */
    protected function getPlatformUserHeader(AuthUser $authUser)
    {
        $appService = AppService::instance();

        $modules = [];
        $configModules = config('navigation.modules');
        foreach ($configModules as $key => $val){
            $modules[] = [
                'name' => $key,
                'url' => OperationContext::bindParams($val['url'])
            ];
        }

        $apps = $appService->getAppsOfUser('123', $authUser->getAuthIdentifier(), ['state'=>true]);
        foreach ($apps as $app){
            $modules[] = [
                'name' => $app['name'],
                'url' => $this->commonGetAppContentUrl($app['id'])
            ];
        }

        return $modules;
    }

    /**
     * 获取应用用户的头部模块
     *
     * @param AuthUser $authUser
     * @return array
     * @throws \Exception
     */
    protected function getAppDeveloperHeader(AuthUser $authUser)
    {
        $appService = AppService::instance();

        $modules = [
            [
                'name' => '应用管理',
                'url' => '/apps'
            ]
        ];

        $apps = $appService->getAppsOfUser('123', $authUser->getAuthIdentifier());

        foreach ($apps as $app){
            if($app['state']){
                $modules[] = [
                    'name' => $app['name'],
                    'url' => $this->commonGetAppContentUrl($app['id'])
                ];
            }
        }

        return $modules;
    }

    /**
     * 获取应用用户的头部模块
     *
     * @param AuthUser $authUser
     * @return array
     * @throws \Exception
     */
    protected function getAppManagerHeader(AuthUser $authUser)
    {
        $appService = AppService::instance();

        $modules = [];

        $apps = $appService->getAppsOfUser('123', $authUser->getAuthIdentifier());

        foreach ($apps as $app){
            if($app['state']){
                $modules[] = [
                    'name' => $app['name'],
                    'url' => $this->commonGetAppContentUrl($app['id'])
                ];
            }
        }

        return $modules;
    }

    /**
     * 获取应用内用户的头部模块
     *
     * @param AuthUser $authUser 用户
     * @return array 模块列表
     */
    protected function getAppContentUserHeader(AuthUser $authUser)
    {
        $appService = AppService::instance();
        $app = $appService->getApp('123', $authUser->appId);

        $modules = [];

        if($app['state']){
            $modules[] = [
                'name' => $app['name'],
                'url' => $this->commonGetAppContentUrl($app['id'])
            ];
        }

        return $modules;
    }

    protected function commonGetAppContentUrl(int $appId)
    {
        return route('indexContentApp', ['appId'=>$appId]);
    }

}