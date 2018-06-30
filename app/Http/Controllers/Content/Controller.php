<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use App\Services\Thing\Service\ThingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * 动态内容基类
 *
 * Class Controller
 * @package App\Http\Controllers\Content
 */
class Controller extends BaseController
{

    /**
     * 设置操作环境内容
     *
     * @param int $appId 应用编号
     * @param int $thingId 事物编号
     * @param bool $isDynamicAction 是否动态Action
     */
    protected function setOperationContext(int $appId, int $thingId=0, bool $isDynamicAction=false)
    {
        OperationContext::setAppId($appId);
        OperationContext::setThingId($thingId);

        if($isDynamicAction){
            $dynamicAction = Route::currentRouteAction().':'.$thingId;
            OperationContext::setDynamicAction($dynamicAction);
        }
    }
}
