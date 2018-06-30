<?php

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Api\Controller;
use App\Services\Thing\Service\ThingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * 事物内容相关接口
 *
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class ContentThingApiController extends Controller
{
    /**
     * @var ThingService
     */
    protected $thingService;

    public function __construct()
    {
        $this->thingService = ThingService::instance();
    }

    /**
     * 新增事物内容
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeContentThing(Request $request)
    {
        try{
            $user = Auth::user();
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $operationId = Route::input('operationId');

            $inputData = $request->all();
            $contextData = [
                'current_user' => $user->getAuthIdentifier(),
                'current_time' => date('Y-m-d H:i:s')
            ];

            $thingContent = $this->thingService->addThingContent('123', $thingId, $operationId, $inputData,
                $contextData);

            if(!empty($thingContent)){
                return $this->successResponse($thingContent);
            }else{
                return $this->failResponse('新增内容失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新事物内容
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateContentThing(Request $request)
    {
        try{
            $user = Auth::user();
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $operationId = Route::input('operationId');
            $contentId = Route::input('contentId');

            $inputData = $request->all();
            $contextData = [
                'current_user' => $user->getAuthIdentifier(),
                'current_time' => date('Y-m-d H:i:s')
            ];

            $thingContent = $this->thingService->updateThingContent('123', $thingId, $operationId,
                $contentId, $inputData, $contextData);

            if(!empty($thingContent)){
                return $this->successResponse($thingContent);

            }else{
                return $this->failResponse('更新内容失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除事物内容
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyContentThing()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $contentId = Route::input('contentId');

            $result = $this->thingService->deleteThingContent('123', $thingId, $contentId);
            if($result){
                return $this->successResponse();
            }else{
                return $this->failResponse('删除失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
