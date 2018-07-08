<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * 用户模块路由
 */
Route::middleware('auth:api')->namespace('Api')->group(function (){

    /*
     * 系统用户角色管理相关接口
     */
    Route::namespace('Platform')->group(function(){

        /*
         * 修改个人信息
         */
        Route::patch('profile', 'PlatformUserApiController@updateProfile')
            ->name('updateProfile');

        /*
         * 创建平台角色
         */
        Route::post('role', 'PlatformRoleApiController@storePlatformRole')
            ->name('storePlatformRole')
            ->middleware('perm:platform');

        /*
         * 更新平台角色
         */
        Route::put('role', 'PlatformRoleApiController@updatePlatformRole')
            ->name('updatePlatformRole')
            ->middleware('perm:platform');

        /*
         * 删除平台角色
         */
        Route::delete('role', 'PlatformRoleApiController@destroyPlatformRole')
            ->name('destroyPlatformRole')
            ->middleware('perm:platform');

        /*
         * 添加角色用户
         */
        Route::post('role/{roleId}/user', 'PlatformRoleApiController@storePlatformRoleUser')
            ->where('roleId', '[1-9][0-9]*')
            ->name('storePlatformRoleUser')
            ->middleware('perm:platform');

        /*
         * 删除角色用户
         */
        Route::delete('role/{roleId}/user/{userId}','PlatformRoleApiController@destroyPlatformRoleUser')
            ->where('roleId', '[1-9][0-9]*')
            ->where('userId', '[1-9][0-9]*')
            ->name('destroyPlatformRoleUser')
            ->middleware('perm:platform');

        /*
         * 删除用户
         */
        Route::delete('user/{userId}', 'PlatformUserApiController@destroyPlatformUser')
            ->where('userId', '[1-9][0-9]*')
            ->name('destroyPlatformUser')
            ->middleware('perm:platform');

        /*
         * 新增平台用户
         */
        Route::post('user', 'PlatformUserApiController@storePlatformUser')
            ->name('storePlatformUser')
            ->middleware('perm:platform');

        /*
         * 更新平台用户
         */
        Route::put('user/{userId}', 'PlatformUserApiController@updatePlatformUser')
            ->where('userId', '[1-9][0-9]*')
            ->name('updatePlatformUser')
            ->middleware('perm:platform');
    });

    /*
     * 应用相关路由（应用、应用角色、应用用户）
     */
    Route::namespace('App')->group(function (){
        /*
         * 新建应用
         */
        Route::post('app', 'AppApiController@storeApp')
            ->name('storeApp')
            ->middleware('perm:appDevelop');

        /*
         * 删除应用
         */
        Route::delete('app/{appId}', 'AppApiController@destroyApp')
            ->where('appId', '[1-9][0-9]*')
            ->name('destroyApp')
            ->middleware('perm:appDevelop');


        /*
         * 更新应用
         */
        Route::put('app/{appId}', 'AppApiController@updateApp')
            ->where('appId', '[1-9][0-9]*')
            ->name('updateApp')
            ->middleware('perm:appDevelop');

        /*
         * 新建应用角色
         */
        Route::post('app/{appId}/role', 'AppRoleApiController@storeAppRole')
            ->where('appId', '[1-9][0-9]*')
            ->name('storeAppRole')
            ->middleware('perm:appDevelop');

        /*
         * 更新应用角色
         */
        Route::put('app/{appId}/role/{roleId}', 'AppRoleApiController@updateAppRole')
            ->where('appId', '[1-9][0-9]*')
            ->where('roleId', '[1-9][0-9]*')
            ->name('updateAppRole')
            ->middleware('perm:appDevelop');

        /*
         * 删除应用角色
         */
        Route::delete('app/{appId}/role/{roleId}', 'AppRoleApiController@destroyAppRole')
            ->where('appId', '[1-9][0-9]*')
            ->where('roleId', '[1-9][0-9]*')
            ->name('destroyAppRole')
            ->middleware('perm:appDevelop');

        /*
         * 创建角色用户
         */
        Route::post('app/{appId}/role/{roleId}/user', 'AppRoleApiController@storeAppRoleUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('roleId', '[1-9][0-9]*')
            ->name('storeAppRoleUser')
            ->middleware('perm:appDevelop');

        /*
         * 删除角色用户
         */
        Route::delete('app/{appId}/role/{roleId}/user/{userId}', 'AppRoleApiController@destroyAppRoleUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('roleId', '[1-9][0-9]*')
            ->where('userId', '[1-9][0-9]*')
            ->name('destroyAppRoleUser')
            ->middleware('perm:appDevelop');

        /*
         * 创建应用用户
         */
        Route::post('app/{appId}/user', 'AppUserApiController@storeAppUser')
            ->where('appId', '[1-9][0-9]*')
            ->name('storeAppUser')
            ->middleware('perm:appDevelop');

        /*
         * 更新应用用户
         */
        Route::put('app/{appId}/user/{userId}', 'AppUserApiController@updateAppUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('userId', '[1-9][0-9]*')
            ->name('updateAppUser')
            ->middleware('perm:appDevelop');

        /*
         * 删除应用用户
         */
        Route::delete('app/{appId}/user/{userId}', 'AppUserApiController@destroyAppUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('roleId', '[1-9][0-9]*')
            ->where('userId', '[1-9][0-9]*')
            ->name('destroyAppUser')
            ->middleware('perm:appDevelop');
    });

    /*
     * 事物管理相关Api
     */
    Route::namespace('Thing')->group(function(){
        /*
         * 创建事物
         */
        Route::post('app/{appId}/thing', 'ThingApiController@storeThing')
            ->where('appId', '[1-9][0-9]*')
            ->name('storeThing')
            ->middleware('perm:appDevelop');

        /*
         * 更新事物
         */
        Route::put('app/{appId}/thing/{thingId}', 'ThingApiController@updateThing')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('updateThing')
            ->middleware('perm:appDevelop');

        /*
         * 删除事物
         */
        Route::delete('app/{appId}/thing/{thingId}', 'ThingApiController@destroyThing')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('destroyThing')
            ->middleware('perm:appDevelop');

        /*
         * 迁移表结构
         */
        Route::put('app/{appId}/thing/{thingId}/migrate','ThingApiController@migrateThing')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('migrateThing')
            ->middleware('perm:appDevelop');

        /*
         * 创建事物字段
         */
        Route::post('app/{appId}/thing/{thingId}/field', 'FieldApiController@storeThingField')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('storeThingField')
            ->middleware('perm:appDevelop');

        /*
         * 更新事物字段
         */
        Route::put('app/{appId}/thing/{thingId}/field/{fieldId}', 'FieldApiController@updateThingField')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('fieldId', '[1-9][0-9]*')
            ->name('updateThingField')
            ->middleware('perm:appDevelop');

        /*
         * 删除事物字段
         */
        Route::delete('app/{appId}/thing/{thingId}/field/{fieldId}', 'FieldApiController@destroyThingField')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('fieldId', '[1-9][0-9]*')
            ->name('destroyThingField')
            ->middleware('perm:appDevelop');

        /*
         * 创建事物状态
         */
        Route::post('app/{appId}/thing/{thingId}/state', 'StateApiController@storeThingState')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('storeThingState')
            ->middleware('perm:appDevelop');

        /*
         * 更新事物状态
         */
        Route::put('app/{appId}/thing/{thingId}/state/{stateId}', 'StateApiController@updateThingState')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('stateId', '[1-9][0-9]*')
            ->name('updateThingState')
            ->middleware('perm:appDevelop');

        /*
         * 删除事物状态
         */
        Route::delete('app/{appId}/thing/{thingId}/state/{stateId}', 'StateApiController@destroyThingState')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('stateId', '[1-9][0-9]*')
            ->name('destroyThingState')
            ->middleware('perm:appDevelop');

        /*
         * 创建事物状态操作
         */
        Route::post('app/{appId}/thing/{thingId}/state/{stateId}/operation', 'StateApiController@storeThingStateOperation')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('stateId', '[1-9][0-9]*')
            ->name('storeThingStateOperation')
            ->middleware('perm:appDevelop');

        /*
         * 删除事物状态操作
         */
        Route::delete('app/{appId}/thing/{thingId}/state/{stateId}/operation/{operationId}', 'StateApiController@destroyThingStateOperation')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('stateId', '[1-9][0-9]*')
            ->where('operationId', '[1-9][0-9]*')
            ->name('destroyThingStateOperation')
            ->middleware('perm:appDevelop');

        /*
         * 创建事物操作
         */
        Route::post('app/{appId}/thing/{thingId}/operation', 'OperationApiController@storeThingOperation')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('storeThingOperation')
            ->middleware('perm:appDevelop');

        /*
         * 更新事物操作
         */
        Route::put('app/{appId}/thing/{thingId}/operation/{operationId}', 'OperationApiController@updateThingOperation')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('operationId', '[1-9][0-9]*')
            ->name('updateThingOperation')
            ->middleware('perm:appDevelop');

        /*
         * 删除事物操作
         */
        Route::delete('app/{appId}/thing/{thingId}/operation/{operationId}', 'OperationApiController@destroyThingOperation')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('operationId', '[1-9][0-9]*')
            ->name('destroyThingOperation')
            ->middleware('perm:appDevelop');

        /*
         * 创建事物消息
         */
        Route::post('app/{appId}/thing/{thingId}/message', 'MessageApiController@storeThingMessage')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('storeThingMessage')
            ->middleware('perm:appDevelop');

        /*
         * 更新事物消息
         */
        Route::put('app/{appId}/thing/{thingId}/message/{messageId}', 'MessageApiController@updateThingMessage')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('messageId', '[1-9][0-9]*')
            ->name('updateThingMessage')
            ->middleware('perm:appDevelop');

        /*
         * 删除事物消息
         */
        Route::delete('app/{appId}/thing/{thingId}/message/{messageId}', 'MessageApiController@destroyThingMessage')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('messageId', '[1-9][0-9]*')
            ->name('destroyThingMessage')
            ->middleware('perm:appDevelop');

        /*
         * 创建事物统计
         */
        Route::post('app/{appId}/thing/{thingId}/statsItem', 'StatsApiController@storeThingStatsItem')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->name('storeThingStatsItem')
            ->middleware('perm:appDevelop');

        /*
         * 更新事物统计
         */
        Route::put('app/{appId}/thing/{thingId}/statsItem/{statsItemId}', 'StatsApiController@updateThingStatsItem')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('statsItemId', '[1-9][0-9]*')
            ->name('updateThingStatsItem')
            ->middleware('perm:appDevelop');

        /*
         * 删除事物统计
         */
        Route::delete('app/{appId}/thing/{thingId}/statsItem/{statsItemId}', 'StatsApiController@destroyThingStatsItem')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('statsItemId', '[1-9][0-9]*')
            ->name('destroyThingStatsItem')
            ->middleware('perm:appDevelop');
    });

    /*
     * 动态内容接口
     */
    Route::namespace('Content')->group(function(){
        /*
         * 添加用户到角色
         */
        Route::post('content/app/{appId}/role/{roleId}/user', 'ContentRoleApiController@storeContentRoleUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('roleId', '[1-9][0-9]*')
            ->name('storeContentRoleUser')
            ->middleware('perm:appManage');

        /*
         * 移除用户从角色
         */
        Route::delete('content/app/{appId}/role/{roleId}/user/{userId}', 'ContentRoleApiController@destroyContentRoleUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('roleId', '[1-9][0-9]*')
            ->where('userId', '[1-9][0-9]*')
            ->name('destroyContentRoleUser')
            ->middleware('perm:appManage');

        /*
         * 创建用户
         */
        Route::post('content/app/{appId}/user', 'ContentUserApiController@storeContentUser')
            ->where('appId', '[1-9][0-9]*')
            ->name('storeContentUser')
            ->middleware('perm:appManage');

        /*
         * 更新用户
         */
        Route::put('content/app/{appId}/user/{userId}', 'ContentUserApiController@updateContentUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('userId', '[1-9][0-9]*')
            ->name('updateContentUser')
            ->middleware('perm:appManage');

        /*
         * 删除用户
         */
        Route::delete('content/app/{appId}/user/{userId}', 'ContentUserApiController@destroyContentUser')
            ->where('appId', '[1-9][0-9]*')
            ->where('userId', '[1-9][0-9]*')
            ->name('destroyContentUser')
            ->middleware('perm:appManage');

        /*
         * 创建事物内容
         */
        Route::post('content/app/{appId}/thing/{thingId}/content/{operationId}', 'ContentThingApiController@storeContentThing')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('operationId', '[1-9][0-9]*')
            ->name('storeContentThing')
            ->middleware('perm:appContent');

        /*
         * 更新事物内容
         */
        Route::put('content/app/{appId}/thing/{thingId}/content/{contentId}/{operationId}', 'ContentThingApiController@updateContentThing')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('contentId', '[1-9][0-9]*')
            ->where('operationId', '[1-9][0-9]*')
            ->name('updateContentThing')
            ->middleware('perm:appContent');

        /*
         * 删除事物内容
         */
        Route::delete('content/app/{appId}/thing/{thingId}/content/{contentId}', 'ContentThingApiController@destroyContentThing')
            ->where('appId', '[1-9][0-9]*')
            ->where('thingId', '[1-9][0-9]*')
            ->where('contentId', '[1-9][0-9]*')
            ->name('destroyContentThing')
            ->middleware('perm:appContent');
    });
});

