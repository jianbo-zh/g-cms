<?php

namespace App\Http\Controllers\Api\Thing;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Thing\PostThingRequest;
use App\Http\Requests\Thing\PutThingRequest;
use App\Services\Thing\Service\ThingService;
use Illuminate\Support\Facades\Route;

/**
 * 事物相关接口
 *
 * Class ThingApiController
 * @package App\Http\Controllers\Api
 */
class ThingApiController extends Controller
{
    /**
     * @var ThingService
     */
    protected $thingService;

    /**
     * ThingApiController constructor.
     */
    public function __construct()
    {
        $this->thingService = ThingService::instance();
    }

    /**
     * 创建事物
     *
     * @param PostThingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThing(PostThingRequest $request)
    {
        try{
            $appId = Route::input('appId');

            $data = $request->only(['name', 'description', 'isAdd']);
            $data['isAdd'] = !empty($data['isAdd']) ? true : false;

            $thing = $this->thingService->addThing('123', $appId, $data['name'],
                $data['description'], $data['isAdd']);

            if(! $thing){
                throw new \Exception('创建事物失败！');
            }

            return $this->successResponse();

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新事物
     *
     * @param PutThingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateThing(PutThingRequest $request)
    {
        try{
            $data = $request->only(['name', 'description', 'isAdd']);

            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $role = $this->thingService->updateThing('123', $thingId, $data['name'], $data['description']);

            if(! $role){
                throw new \Exception('更新应用失败！');
            }

            return $this->successResponse();

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除事物
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyThing()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $result = $this->thingService->deleteThing('123', $thingId);

            if($result){
                return $this->successResponse();

            }else{
                return $this->failResponse('删除失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建或更新事物表结构
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function migrateThing()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $result = $this->thingService->migrateThing('123', $thingId);
            if($result){
                return $this->successResponse();
            }else{
                return $this->failResponse('迁移失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
