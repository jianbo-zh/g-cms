<?php

namespace App\Http\Controllers\Thing;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use App\Services\Thing\Service\ThingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * 事物管理基类
 *
 * Class Controller
 * @package App\Http\Controllers\Content
 */
class Controller extends BaseController
{

    protected function setOperationContext(int $appId, int $thingId=0)
    {
        OperationContext::setAppId($appId);
        OperationContext::setThingId($thingId);
    }
}
