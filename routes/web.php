<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')
    ->name('homeIndex')
    ->middleware('auth');

Route::get('/my-app', 'AppHomeController@index')
    ->name('appHomeIndex')
    ->middleware(['auth', 'perm:appContent']);

/*
 * 平台相关列表、表单
 */
Route::middleware('auth')->namespace('Platform')->group(function(){
    Route::get('/profile', 'PlatformUserController@editProfile')
        ->name('editProfile');

    Route::post('/profile', 'PlatformUserController@updateProfile')
        ->name('updateProfile');

    /*
     * 平台角色列表
     */
    Route::get('/roles', 'PlatformRoleController@indexPlatformRoles')
        ->name('indexPlatformRoles')
        ->middleware(['auth', 'perm:platform']);

    /*
     * 平台角色用户列表
     */
    Route::get('/role/{id}/users', 'PlatformRoleController@indexPlatformRoleUsers')
        ->name('indexPlatformRoleUsers')
        ->middleware(['auth', 'perm:platform']);

    /*
     * 编辑平台角色
     */
    Route::get('/role/{id}/edit', 'PlatformRoleController@editPlatformRole')
        ->name('editPlatformRole')
        ->middleware(['auth', 'perm:platform']);

    /*
     * 创建平台角色
     */
    Route::get('/role/create', 'PlatformRoleController@createPlatformRole')
        ->name('createPlatformRole')
        ->middleware(['auth', 'perm:platform']);

    /*
     * 创建角色用户
     */
    Route::get('/role/{roleId}/user/create', 'PlatformRoleController@createPlatformRoleUser')
        ->name('createPlatformRoleUser')
        ->middleware(['auth', 'perm:platform']);

    /*
     * 平台用户列表
     */
    Route::get('/users', 'PlatformUserController@indexPlatformUsers')
        ->name('indexPlatformUsers')
        ->middleware(['auth', 'perm:platform']);

    /*
     * 创建平台用户
     */
    Route::get('/user/create', 'PlatformUserController@createPlatformUser')
        ->name('createPlatformUser')
        ->middleware(['auth', 'perm:platform']);

    /*
     * 修改平台用户
     */
    Route::get('/user/{userId}/edit', 'PlatformUserController@editPlatformUser')
        ->name('editPlatformUser')
        ->middleware(['auth', 'perm:platform']);
});

/*
 * 应用管理相关列表、表单
 */
Route::middleware('auth')->namespace('App')->group(function (){

    /*
     * 应用列表
     */
    Route::get('/apps', 'AppController@indexApps')
        ->name('indexApps')
        ->middleware('perm:appDevelop');

    /*
     * 创建应用
     */
    Route::get('/app/create', 'AppController@createApp')
        ->name('createApp')
        ->middleware('perm:appDevelop');

    /*
     * 编辑应用
     */
    Route::get('/app/{appId}/edit', 'AppController@editApp')
        ->name('editApp')
        ->middleware('perm:appDevelop');

    /*
     * 应用主页
     */
    Route::get('/app/{appId}/manage', 'AppController@manageApp')
        ->name('manageApp');

    /*
     * 应用角色-列表
     */
    Route::get('/app/{appId}/roles', 'AppRoleController@indexAppRoles')
        ->name('indexAppRoles')
        ->middleware('perm:appDevelop');

    /*
     * 应用角色-新增
     */
    Route::get('/app/{appId}/role/create', 'AppRoleController@createAppRole')
        ->where('appId', '[1-9][0-9]*')
        ->name('createAppRole')
        ->middleware('perm:appDevelop');

    /*
     * 应用角色-编辑
     */
    Route::get('/app/{appId}/role/{roleId}/edit', 'AppRoleController@editAppRole')
        ->where('appId', '[1-9][0-9]*')
        ->where('roleId', '[1-9][0-9]*')
        ->name('editAppRole')
        ->middleware('perm:appDevelop');

    /*
     * 应用角色用户-列表
     */
    Route::get('/app/{appId}/role/{roleId}/users', 'AppRoleController@indexAppRoleUsers')
        ->where('appId', '[1-9][0-9]*')
        ->where('roleId', '[1-9][0-9]*')
        ->name('indexAppRoleUsers')
        ->middleware('perm:appDevelop');

    /*
     * 应用用户-列表
     */
    Route::get('/app/{appId}/users', 'AppUserController@indexAppUsers')
        ->where('appId', '[1-9][0-9]*')
        ->name('indexAppUsers')
        ->middleware('perm:appDevelop');

    /*
     * 应用用户-新增
     */
    Route::get('/app/{appId}/user/create', 'AppUserController@createAppUser')
        ->where('appId', '[1-9][0-9]*')
        ->name('createAppUser')
        ->middleware('perm:appDevelop');

    /*
     * 应用用户-编辑
     */
    Route::get('/app/{appId}/user/{userId}/edit', 'AppUserController@editAppUser')
        ->where('appId', '[1-9][0-9]*')
        ->where('userId', '[1-9][0-9]*')
        ->name('editAppUser')
        ->middleware('perm:appDevelop');
});

/*
 * 事物管理相关列表、表单
 */
Route::middleware('auth')->namespace('Thing')->group(function(){

    /*
     * 事物-列表
     */
    Route::get('/app/{appId}/things', 'ThingController@indexThings')
        ->name('indexThings')
        ->middleware('perm:appDevelop');

    /*
     * 事物-新增
     */
    Route::get('/app/{appId}/thing/create', 'ThingController@createThing')
        ->name('createThing')
        ->middleware('perm:appDevelop');

    /*
     * 事物-编辑
     */
    Route::get('/app/{appId}/thing/{thingId}/edit', 'ThingController@editThing')
        ->name('editThing')
        ->middleware('perm:appDevelop');

    /*
     * 事物-管理
     */
    Route::get('/app/{appId}/thing/{thingId}/manage', 'ThingController@manageThing')
        ->name('manageThing')
        ->middleware('perm:appDevelop');

    /*
     * 事物字段-列表
     */
    Route::get('/app/{appId}/thing/{thingId}/fields', 'FieldController@indexThingFields')
        ->name('indexThingFields')
        ->middleware('perm:appDevelop');

    /*
     * 事物字段-新增
     */
    Route::get('/app/{appId}/thing/{thingId}/field/create', 'FieldController@createThingField')
        ->name('createThingField')
        ->middleware('perm:appDevelop');

    /*
     * 事物字段-编辑
     */
    Route::get('/app/{appId}/thing/{thingId}/field/{fieldId}/edit', 'FieldController@editThingField')
        ->name('editThingField')
        ->middleware('perm:appDevelop');

    /*
     * 事物状态-列表
     */
    Route::get('/app/{appId}/thing/{thingId}/states', 'StateController@indexThingStates')
        ->name('indexThingStates')
        ->middleware('perm:appDevelop');

    /*
     * 事物状态-新增
     */
    Route::get('/app/{appId}/thing/{thingId}/state/create', 'StateController@createThingState')
        ->name('createThingState')
        ->middleware('perm:appDevelop');

    /*
     * 事物状态-编辑
     */
    Route::get('/app/{appId}/thing/{thingId}/state/{stateId}/edit', 'StateController@editThingState')
        ->name('editThingState')
        ->middleware('perm:appDevelop');

    /*
     * 事物状态操作-列表
     */
    Route::get('/app/{appId}/thing/{thingId}/state/{stateId}/operations', 'StateController@indexThingStateOperations')
        ->name('indexThingStateOperations')
        ->middleware('perm:appDevelop');

    /*
     * 事物操作-列表
     */
    Route::get('/app/{appId}/thing/{thingId}/operations', 'OperationController@indexThingOperations')
        ->name('indexThingOperations')
        ->middleware('perm:appDevelop');

    /*
     * 事物操作-新增
     */
    Route::get('/app/{appId}/thing/{thingId}/operation/create', 'OperationController@createThingOperation')
        ->name('createThingOperation')
        ->middleware('perm:appDevelop');

    /*
     * 事物操作-编辑
     */
    Route::get('/app/{appId}/thing/{thingId}/operation/{operationId}/edit', 'OperationController@editThingOperation')
        ->name('editThingOperation')
        ->middleware('perm:appDevelop');

    /*
     * 事物消息-列表
     */
    Route::get('/app/{appId}/thing/{thingId}/messages', 'MessageController@indexThingMessages')
        ->name('indexThingMessages')
        ->middleware('perm:appDevelop');

    /*
     * 事物消息-新增
     */
    Route::get('/app/{appId}/thing/{thingId}/message/create', 'MessageController@createThingMessage')
        ->name('createThingMessage')
        ->middleware('perm:appDevelop');

    /*
     * 事物消息-编辑
     */
    Route::get('/app/{appId}/thing/{thingId}/message/{messageId}/edit', 'MessageController@editThingMessage')
        ->name('editThingMessage')
        ->middleware('perm:appDevelop');

    /*
     * 事物统计-列表
     */
    Route::get('/app/{appId}/thing/{thingId}/statsItems', 'StatsController@indexThingStatsItems')
        ->name('indexThingStatsItems')
        ->middleware('perm:appDevelop');

    /*
     * 事物统计-新增
     */
    Route::get('/app/{appId}/thing/{thingId}/statsItem/create', 'StatsController@createThingStatsItem')
        ->name('createThingStatsItem')
        ->middleware('perm:appDevelop');

    /*
     * 事物统计-编辑
     */
    Route::get('/app/{appId}/thing/{thingId}/statsItem/{stateItemId}/edit', 'StatsController@editThingStatsItem')
        ->name('editThingStatsItem')
        ->middleware('perm:appDevelop');


});

/*
 * 动态内容相关列表、表单
 */
Route::middleware('auth')->namespace('Content')->group(function (){

    /*
     * 应用首页
     */
    Route::get('/content/app/{appId}', 'ContentAppController@indexContentApp')
        ->where('appId', '[1-9][0-9]*')
        ->name('indexContentApp')
        ->middleware('perm:appContent');

    /*
     * 角色-列表
     */
    Route::get('/content/app/{appId}/roles', 'ContentRoleController@indexContentRoles')
        ->where('appId', '[1-9][0-9]*')
        ->name('indexContentRoles')
        ->middleware('perm:appManage');

    /*
     * 角色用户-列表
     */
    Route::get('/content/app/{appId}/role/{roleId}/users', 'ContentRoleController@indexContentRoleUsers')
        ->where('appId', '[1-9][0-9]*')
        ->where('roleId', '[1-9][0-9]*')
        ->name('indexContentRoleUsers')
        ->middleware('perm:appManage');

    /*
     * 用户-列表
     */
    Route::get('/content/app/{appId}/users', 'ContentUserController@indexContentUsers')
        ->where('appId', '[1-9][0-9]*')
        ->name('indexContentUsers')
        ->middleware('perm:appManage');

    /*
     * 用户-新增
     */
    Route::get('/content/app/{appId}/user/create', 'ContentUserController@createContentUser')
        ->where('appId', '[1-9][0-9]*')
        ->name('createContentUser')
        ->middleware('perm:appManage');

    /*
     * 用户-编辑
     */
    Route::get('/content/app/{appId}/user/{userId}/edit', 'ContentUserController@editContentUser')
        ->where('appId', '[1-9][0-9]*')
        ->where('userId', '[1-9][0-9]*')
        ->name('editContentUser')
        ->middleware('perm:appManage');

    /*
     * 内容-列表
     */
    Route::get('/content/app/{appId}/thing/{thingId}/contents', 'ContentThingController@indexContentThings')
        ->where('appId', '[1-9][0-9]*')
        ->where('thingId', '[1-9][0-9]*')
        ->name('indexContentThings')
        ->middleware('perm:appContent');

    /*
     * 内容-新增
     */
    Route::get('/content/app/{appId}/thing/{thingId}/content/create/{operationId}', 'ContentThingController@createContentThing')
        ->where('appId', '[1-9][0-9]*')
        ->where('thingId', '[1-9][0-9]*')
        ->where('operationId', '[1-9][0-9]*')
        ->name('createContentThing')
        ->middleware('perm:appContent');

    /*
     * 内容-编辑
     */
    Route::get('/content/app/{appId}/thing/{thingId}/content/{contentId}/edit/{operationId}', 'ContentThingController@editContentThing')
        ->where('appId', '[1-9][0-9]*')
        ->where('thingId', '[1-9][0-9]*')
        ->where('contentId', '[1-9][0-9]*')
        ->where('operationId', '[1-9][0-9]*')
        ->name('editContentThing')
        ->middleware('perm:appContent');
});
