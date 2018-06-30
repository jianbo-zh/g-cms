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


class ContentUserController extends Controller
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
     * UserController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->roleService = RoleService::instance();
        $this->userService = UserService::instance();
    }

    /**
     * 应用用户列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexContentUsers(Request $request)
    {
        try{
            $appId = Route::input('appId');

            $this->setOperationContext($appId);

            $query = [
                'username' => $request->query('username'),
                'state' => $request->query('state'),
            ];

            $users = $this->userService->getAppUsers('123', $appId, $query);

            return view('content.indexContentUsers', [
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createContentUser()
    {
        try{
            $appId = Route::input('appId');

            $this->setOperationContext($appId);

            return view('content.createContentUser', [
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
    public function editContentUser()
    {
        try{
            $appId = Route::input('appId');
            $userId = Route::input('userId');

            $this->setOperationContext($appId);

            $user = $this->userService->getUser('123', $userId);

            return view('content.editContentUser', [
                'appId' => $appId,
                'user' => $user,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
