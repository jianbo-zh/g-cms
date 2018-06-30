<?php

namespace App\Policies;

use App\Http\Libraries\AuthUser;
use App\Services\App\Service\AppService;
use App\Services\Thing\Service\OperationService;
use App\Services\User\Model\User;
use App\Services\User\Service\RoleService;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class BusinessPrivilegePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 检查是否有对应的平台权限
     *
     * @param AuthUser $authUser
     * @param $perm
     * @return bool
     */
    public function platformAuth(AuthUser $authUser, $perm=null)
    {
        $roleService = RoleService::instance();

        if($this->checkIfSuperAdmin($authUser)){
            return true;
        }

        $hadPerms = $roleService->getPlatformPermsOfUser('123', $authUser->getAuthIdentifier());
        if(is_null($perm)){
            $perm = Route::currentRouteAction();
        }

        return in_array($perm, $hadPerms) ? true : false;
    }

    /**
     *检查是否有App应用开发者权限
     *
     * @param AuthUser $authUser
     * @param null $params
     * @return bool
     * @throws \Exception
     */
    public function appDevelopAuth(AuthUser $authUser, $params=null)
    {
        $appService = AppService::instance();

        if($this->checkIfSuperAdmin($authUser)){
            return true;
        }

        if(is_null($params)){
            $appId = Route::input('appId');

        }else{
            if(empty($params['appId'])){
                throw new \Exception('参数错误，appId不能为空！');
            }
            $appId = $params['appId'];
        }

        if($authUser->checkIsPlatformUser() || $authUser->checkIsAppDeveloper()){
            if(! $appId){
                return true;

            }else{
                $appIds = $appService->getAppIdsOfUser('123', $authUser->getAuthIdentifier());
                if(in_array($appId, $appIds)){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 检查是否有App应用管理者权限
     *
     * @param AuthUser $authUser
     * @param null $params
     * @return bool
     * @throws \Exception
     */
    public function appManageAuth(AuthUser $authUser, $params=null)
    {
        $appService = AppService::instance();

        if($this->checkIfSuperAdmin($authUser)){
            return true;
        }

        if(is_null($params)){
            $appId = Route::input('appId');
        }else{
            if(empty($params['appId'])){
                throw new \Exception('参数错误，appId不能为空！');
            }
            $appId = $params['appId'];
        }
        if($authUser->checkIsPlatformUser() || $authUser->checkIsAppManager()){
            if(! $appId){
                return true;

            }else{
                $appIds = $appService->getAppIdsOfUser('123', $authUser->getAuthIdentifier());

                if(in_array($appId, $appIds)){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 检查是否有App应用内容运营权限
     *
     * @param AuthUser $authUser
     * @param array $params
     * @return bool
     */
    public function appContentAuth(AuthUser $authUser, $params=null)
    {
        $appService = AppService::instance();
        $operationService = OperationService::instance();

        if($this->checkIfSuperAdmin($authUser)){
            return true;
        }

        if(is_null($params)){
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $operationId = Route::input('operationId');

        }else{
            $appId = $params['appId'];
            $thingId = $params['thingId'];
            $operationId = $params['operationId'];
        }

        if($authUser->checkIsPlatformUser() || $authUser->checkIsAppDeveloperOrManager()){
            if(! $appId){
                return true;
            }
            $appIds = $appService->getAppIdsOfUser('123', $authUser->getAuthIdentifier());
            if(in_array($appId, $appIds)){
                return true;
            }

        }else if($authUser->checkIsAppContentUser()){
            if(! $operationId){
                return true;
            }
            $operationIds = $operationService->getOperationIdsOfUser('123', $appId, $thingId,
                $authUser->getAuthIdentifier());

            if(in_array($operationId, $operationIds)){
                return true;
            }
        }

        return false;
    }

    /**
     * 检查是否是系统的超级管理
     *
     * @param AuthUser $authUser
     * @return bool
     */
    protected function checkIfSuperAdmin(AuthUser $authUser)
    {
        if($authUser->getAuthIdentifier() === 1){
            return true;
        }

        return false;
    }
}
