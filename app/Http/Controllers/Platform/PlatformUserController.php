<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\User\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PlatformUserController extends Controller
{
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

        $this->userService = UserService::instance();
    }

    /**
     * 编辑个人配置表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile()
    {
        $user = Auth::user();

        return view('profile', ['user' => $user->toArray()]);
    }

    /**
     * 平台用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexPlatformUsers(Request $request)
    {
        try{
            $condition = [
                'username'  => $request->query('username'),
                'userType'  => $request->query('userType'),
                'state'     => $request->query('state'),
            ];
            $condition['state'] = !is_null($condition['state']) ? (bool)$condition['state'] : null;

            $users = $this->userService->getUsers('123', $condition);

            $userTypes = $this->userService->getUserTypes();

            return view('platform.user.indexPlatformUsers', [
                'query' => $condition,
                'userTypes' => $userTypes,
                'users' => $users
            ]);


        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建平台用户表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createPlatformUser()
    {
        try{

            return view('platform.user.createPlatformUser');

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑平台用户
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPlatformUser()
    {
        try{
            $userId = Route::input('userId');

            $user = $this->userService->getUser('123', $userId);

            return view('platform.user.editPlatformUser', [
                'user' => $user
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
