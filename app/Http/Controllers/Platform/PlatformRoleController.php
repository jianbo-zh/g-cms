<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlatformRoleController extends Controller
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
     * 创建角色表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function createPlatformRole()
    {
        try{
            $permissions = $this->commonGetPermissions();

            return view('platform.user.createPlatformRole',['permissions' => $permissions]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 平台角色列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexPlatformRoles(Request $request)
    {
        $state = $request->input('state');

        $roles = $this->roleService->getPlatformRoles('123', $state);

        return view('platform.user.indexPlatformRoles', ['roles' => $roles]);
    }

    /**
     * 平台角色用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexPlatformRoleUsers()
    {
        try{
            $roleId = Route::input('id');

            $role = $this->roleService->getPlatformRole('123', $roleId);

            $users = $this->userService->getUsersBelongToRole('123', $roleId, ['id', 'username', 'nickname',
                'avatar', 'phone', 'email', 'state']);

            $notBelongToRoleUsers = $this->userService->getPlatformUsersNotBelongToRole('123', $roleId);

            return view('platform.user.indexPlatformRoleUsers', [
                'role'  => $role,
                'users' => $users,
                'notBelongToRoleUsers' => $notBelongToRoleUsers,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑角色表单
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPlatformRole($id)
    {
        try{
            $role = $this->roleService->getPlatformRole('123', $id);

            $permissions = $this->commonGetPermissions();

            return view('platform.user.editPlatformRole', [
                'permissions' => $permissions,
                'role' => $role
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 获取系统配置权限列表
     *
     * @return array
     * @throws \Exception
     * @throws \ReflectionException
     */
    private function commonGetPermissions()
    {
        $permissions = config('permission');
        foreach ($permissions as $key1 => $module){
            foreach ($module['groups'] as $key2 => $group){
                if(is_string($group['perms'])){
                    $perms = $this->commonGetClassPerms($group['name'], $group['perms']);
                    $permissions[$key1]['groups'][$key2]['perms'] = $perms;
                }
            }
        }

        return $permissions;
    }


    /**
     * 公共获取控制器类的访问权限
     *
     * @param string $groupName 权限分组名称
     * @param string $permClass 控制器类
     * @return array 公共可访问的权限
     * @throws \Exception
     * @throws \ReflectionException
     */
    private function commonGetClassPerms(string $groupName, string $permClass)
    {
        $perms = [];
        if(! class_exists($permClass)){
            throw new \Exception('权限配置错误！' . $groupName . '的权限必须是控制器类！');
        }
        $oReflectionClass = new \ReflectionClass($permClass);
        $reflectionMethods = $oReflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($reflectionMethods as $reflectionMethod){
            if(
                $reflectionMethod->class === $permClass &&
                strpos($reflectionMethod->name, '__') === false
            ){
                $permKey = $permClass . '@' . $reflectionMethod->name;

                if($comment = $reflectionMethod->getDocComment()){
                    if(preg_match("/\/\*\*\r{0,1}\n\s+\*\s+(.*){0,1}\n.*/", $comment, $match) > 0){
                        $perms[$permKey] = !empty($match[1]) ? $match[1] : '';

                    }else{
                        throw new \Exception('方法'. $permKey .'的说明文档不规范！' . $comment);
                    }
                }else{
                    throw new \Exception('未找到方法'. $permKey .'的说明文档！');
                }
            }
        }

        return $perms;
    }
}
