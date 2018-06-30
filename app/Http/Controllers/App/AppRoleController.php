<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\App\Service\AppService;
use App\Services\Thing\Service\OperationService;
use App\Services\Thing\Service\ThingService;
use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Support\Facades\Route;


class AppRoleController extends Controller
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
     * @var ThingService
     */
    protected $thingService;

    /**
     * @var OperationService
     */
    protected $operationService;

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
        $this->thingService = ThingService::instance();
        $this->operationService = OperationService::instance();
    }

    /**
     * 应用角色列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAppRoles()
    {
        try{
            $appId = Route::input('appId');

            $roleService = RoleService::instance();

            $roles = $roleService->getAppRoles('123', $appId, []);

            return view('platform.app.indexAppRoles', [
                'appId' => $appId,
                'roles' => $roles
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建应用角色表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createAppRole()
    {
        try{
            $appId = Route::input('appId');

            $app = $this->appService->getApp('123', $appId);
            $things = $this->thingService->getThings('123', $appId);
            $permissions = [
                'name' => $app['name'],
                'groups' => [],
            ];

            foreach ($things as $thing){
                $tmp = [
                    'name' => $thing['name'],
                    'perms' => []
                ];
                $operations = $this->operationService->getOperations('123', $thing['id']);
                foreach ($operations as $operation){
                    $tmp['perms'][$operation['permCode']] = $operation['name'];
                }

                $permissions['groups'][] = $tmp;
            }

            return view('platform.app.createAppRole', [
                'appId' => $appId,
                'permissions' => [$permissions]
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑引用角色表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editAppRole()
    {
        try{
            $appId = Route::input('appId');
            $roleId = Route::input('roleId');

            $app = $this->appService->getApp('123', $appId);
            $things = $this->thingService->getThings('123', $appId);
            $permissions = [
                'name' => $app['name'],
                'groups' => [],
            ];

            foreach ($things as $thing){
                $tmp = [
                    'name' => $thing['name'],
                    'perms' => []
                ];
                $operations = $this->operationService->getOperations('123', $thing['id']);
                foreach ($operations as $operation){
                    $tmp['perms'][$operation['permCode']] = $operation['name'];
                }

                $permissions['groups'][] = $tmp;
            }

            $role = $this->roleService->getAppRole('123', $appId, $roleId);

            return view('platform.app.editAppRole', [
                'appId' => $appId,
                'permissions' => [$permissions],
                'role' => $role,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 应用角色用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAppRoleUsers()
    {
        try{
            $appId = Route::input('appId');
            $roleId = Route::input('roleId');

            $app = $this->appService->getApp('123', $appId);

            $belongToRoleUsers = $this->userService->getUsersBelongToRole('123', $roleId, ['*']);
            $belongToRoleUserMap = [];
            foreach ($belongToRoleUsers as $user){
                $belongToRoleUserMap[$user['id']] = $user;
            }

            $notBelongToRoleUsers = [];

            if(empty($belongToRoleUserMap[$app['userId']])){
                $notBelongToRoleUsers[] = $this->userService->getUser('123', $app['userId'], ['*']);
            }

            $appUsers = $this->userService->getAppUsers('123', $appId);
            foreach ($appUsers as $user){
                if(empty($belongToRoleUserMap[$user['id']])){
                    $notBelongToRoleUsers[] = $user;
                }
            }


            return view('platform.app.indexAppRoleUsers', [
                'appId' => $appId,
                'roleId' => $roleId,
                'users' => $belongToRoleUsers,
                'notBelongToRoleUsers' => $notBelongToRoleUsers,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
