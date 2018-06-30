<?php

namespace App\Http\ViewComposers;

use App\Http\Controllers\Content\ContentRoleController;
use App\Http\Controllers\Content\ContentThingController;
use App\Http\Controllers\Content\ContentUserController;
use App\Http\Libraries\AuthUser;
use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use App\Services\Thing\Service\ThingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class SidebarComposer
{

    /**
     * 获取当前的左侧菜单
     *
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $menus = config('navigation.sidebars');
        $accessNodes = config('navigation.accessNodes');

        $curMenuName = OperationContext::getCurrentMenuName();
        $curAction = Route::currentRouteAction();

        if(isset($accessNodes[$curAction])){
            $groupName = $this->getUserMenuGroupName();
            $allowMenus = $accessNodes[$curAction][1];
            if(!empty($allowMenus[$groupName])){
                $curMenuName = $allowMenus[$groupName];

            }else if(!empty($allowMenus['common'])){
                $curMenuName = $allowMenus['common'];

            }else{
                throw new \Exception('当前节点未配置菜单！');
            }

            OperationContext::setCurrentMenuName($curMenuName);
        }

        if(is_null($curMenuName)){
            throw new \Exception('未找到当前对应的菜单！'.$curAction);
        }

        list($group, $name) = explode('.', $curMenuName);

        if(!isset($menus[$group][$name])){
            throw new \Exception('未找到名字为'. $curMenuName .'的菜单！');
        }

        $menu = $this->mergeDynamicMenu($menus[$group][$name]);

        $view->with('currentAction', $curAction);
        $view->with('menu', $menu);
    }

    /**
     * 检测是否有动态菜单，如果有则生成并合并
     *
     * @param $menu
     * @return array
     * @throws \Exception
     */
    protected function mergeDynamicMenu($menu)
    {
        $mergedMenu = [];
        foreach ($menu as $key => $value){
            if($key === '_DYNAMIC_MENU_'){
                $dynamicMenu = $this->getDynamicMenu($value);
                foreach ($dynamicMenu as $key2 => $value2){
                    $mergedMenu[$key2] = $value2;
                }

            }else{
                if(! isset($value['action'])){    // 下面还有菜单
                    $newValue = [];
                    foreach ($value as $key3 => $value3){
                        if($key3 === '_DYNAMIC_MENU_'){
                            $dynamicMenu = $this->getDynamicMenu($value3);
                            foreach ($dynamicMenu as $key2 => $value2){
                                $newValue[$key2] = $value2;
                            }
                        }else{
                            $newValue[$key3] = $value3;
                        }
                    }
                    $mergedMenu[$key] = $newValue;

                }else{
                    $mergedMenu[$key] = $value;
                }

            }
        }

        return $mergedMenu;
    }

    /**
     * 获取动态菜单
     *
     * @param $dynamicFlag
     * @return array
     * @throws \Exception
     */
    protected function getDynamicMenu($dynamicFlag)
    {
        $menu = [];
        switch ($dynamicFlag){
            case '_THING_LIST_':
                $menu = $this->getDynamicMenuOfTingList();
                break;
        }

        return $menu;
    }

    /**
     * 获取应用的事物列表菜单
     *
     * @return array
     * @throws \Exception
     */
    protected function getDynamicMenuOfTingList()
    {
        $thingService = ThingService::instance();

        $authUser = $this->getAuthUser();
        $appId = OperationContext::getAppId();
        if(! $appId){
            throw new \Exception('未设置应用编号！');
        }

        $menu = [];

        if($authUser->checkIsAppManager()){
            $menu['角色管理'] = [
                'icon'      => 'icon-people',
                'action'    => ContentRoleController::class . '@indexContentRoles',
                'url'       => \route('indexContentRoles', ['appId'=>$appId]),
            ];

            $menu['用户管理'] = [
                'icon'      => 'icon-user',
                'action'    => ContentUserController::class . '@indexContentUsers',
                'url'       => \route('indexContentUsers', ['appId'=>$appId]),
            ];
        }

        $things = $thingService->getThings('123', $appId);
        foreach ($things as $thing){
            $menu[$thing['name']] = [
                'icon'      => 'icon-docs',
                'action'    => ContentThingController::class . '@indexContentThings:' . $thing['id'],
                'url'       => \route('indexContentThings', ['appId'=>$appId, 'thingId'=>$thing['id']]),
            ];
        }

        return $menu;
    }

    /**
     * 获取当前用户菜单类型
     *
     * @return string
     */
    protected function getUserMenuGroupName()
    {
        $name = '';
        $authUser = $this->getAuthUser();
        if($authUser->checkIsPlatformUser()){
            $name = 'platform';

        }else if($authUser->checkIsAppDeveloper()){
            $name = 'developer';

        }else if($authUser->checkIsAppManager()){
            $name = 'normal';

        }else if($authUser->checkIsAppContentUser()){
            $name = 'content';
        }

        return $name;
    }

    /**
     * @return AuthUser|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getAuthUser()
    {
        return Auth::user();
    }
}