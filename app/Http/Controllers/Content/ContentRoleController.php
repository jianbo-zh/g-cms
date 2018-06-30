<?php

namespace App\Http\Controllers\Content;

use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use App\Services\Thing\Service\ThingService;
use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class ContentRoleController extends Controller
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
     * RoleController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->roleService = RoleService::instance();
        $this->userService = UserService::instance();
        $this->appService = AppService::instance();
    }

    /**
     * 应用角色列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexContentRoles()
    {
        try{
            $appId = Route::input('appId');

            $roles = $this->roleService->getAppRoles('123', $appId);

            $this->setOperationContext($appId);

            return view('content.indexContentRoles', [
                    'appId' => $appId,
                    'roles' => $roles,
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
    public function indexContentRoleUsers()
    {
        try{
            $appId = Route::input('appId');
            $roleId = Route::input('roleId');

            $this->setOperationContext($appId);

            $app = $this->appService->getApp('123', $appId);

            $belongToRoleUsers = $this->userService->getUsersBelongToRole('123', $roleId, ['id', 'username',
                'nickname', 'avatar', 'phone', 'email', 'state']);
            $belongToRoleUserMap = [];
            foreach ($belongToRoleUsers as $user){
                $belongToRoleUserMap[$user['id']] = $user;
            }

            $notBelongToRoleUsers = [];

            if(empty($belongToRoleUserMap[$app['userId']])){
                $notBelongToRoleUsers[] = $this->userService->getUser('123', $app['userId'], ['id', 'username', 'nickname',
                    'avatar', 'phone', 'email', 'state']);
            }

            $appUsers = $this->userService->getAppUsers('123', $appId);
            foreach ($appUsers as $user){
                if(empty($belongToRoleUserMap[$user['id']])){
                    $notBelongToRoleUsers[] = $user;
                }
            }

            return view('content.indexContentRoleUsers', [
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
