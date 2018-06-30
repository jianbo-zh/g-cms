<?php

namespace App\Http\Controllers;

use App\Http\Libraries\AuthUser;
use App\Http\Libraries\OperationContext;
use App\Http\Requests\PatchProfileRequest;
use App\Services\App\Service\AppService;
use App\Services\Thing\Model\ThingModel;
use App\Services\Thing\Service\ThingService;
use App\Services\User\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 首页展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if($redirectTo = $this->getRedirectTo()){
            return redirect($redirectTo);
        }

        return view('welcome');
    }

    /**
     * 检查是否需要跳转
     *
     * 普通用户或应用用户需要跳转到他们的管理页面
     *
     * @return null|string
     */
    private function getRedirectTo()
    {
        $authUser = $this->getAuthUser();

        $redirectTo = null;

        if($authUser->checkIsAppDeveloper()){
            $redirectTo = route('appHomeIndex');

        }else if($authUser->checkIsAppManager()){
            $redirectTo = route('appHomeIndex');

        }else if($authUser->checkIsAppContentUser()){
            $redirectTo = route('indexContentApp', ['appId'=>$authUser->appId]);
        }

        return $redirectTo;
    }

    /**
     * @return AuthUser|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    private function getAuthUser()
    {
        return Auth::user();
    }
}
