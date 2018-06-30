<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\App\Service\AppService;
use App\Services\User\Service\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 指定账户字段
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * 获取登陆成功后跳转地址，及每次登陆成功后台，更新用户ApiToken
     *
     * @return string
     * @throws \Exception
     */
    public function redirectTo()
    {
        $redirectTo = '/';
        $authUser = Auth::user();

        $userService = UserService::instance();

        $result = $userService->updateUserApiToken('123', $authUser->getAuthIdentifier());
        if(! $result){
            throw new \Exception('更新用户ApiToken失败！');
        }

        return $redirectTo;
    }
}
