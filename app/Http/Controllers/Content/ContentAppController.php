<?php

namespace App\Http\Controllers\Content;

use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Support\Facades\Route;


class ContentAppController extends Controller
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
     * AppController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->roleService = RoleService::instance();
        $this->userService = UserService::instance();
    }

    /**
     * 应用主页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexContentApp()
    {
        try{
            $appId = Route::input('appId');

            $this->setOperationContext($appId);

            return view('content.indexContentApp');

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
