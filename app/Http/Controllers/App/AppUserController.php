<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class AppUserController extends Controller
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->roleService = RoleService::instance();
        $this->userService = UserService::instance();
    }

    /**
     * 应用用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAppUsers(Request $request)
    {
        try{
            $appId = Route::input('appId');

            $query = [
                'username' => $request->query('username'),
                'state' => $request->query('state'),
            ];

            $users = $this->userService->getAppUsers('123', $appId, $query);

            return view('platform.app.indexAppUsers', [
                'appId' => $appId,
                'users' => $users,
                'query' => $query
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建应用用户
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createAppUser()
    {
        try{
            $appId = Route::input('appId');

            return view('platform.app.createAppUser', [
                'appId' => $appId,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑应用用户
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editAppUser()
    {
        try{
            $appId = Route::input('appId');
            $userId = Route::input('userId');

            $user = $this->userService->getUser('123', $userId);

            return view('platform.app.editAppUser', [
                'appId' => $appId,
                'user' => $user,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
