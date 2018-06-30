<?php

namespace App\Http\Controllers\Api\Thing;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Thing\PostThingStateOperationRequest;
use App\Http\Requests\Thing\PostThingStateRequest;
use App\Http\Requests\Thing\PutThingStateRequest;
use App\Services\Thing\Service\StateService;
use Illuminate\Support\Facades\Route;

/**
 * 事物字段相关接口
 *
 * Class ThingApiController
 * @package App\Http\Controllers\Api
 */
class StateApiController extends Controller
{
    /**
     * @var StateService
     */
    protected $stateService;

    /**
     * StateApiController constructor.
     */
    public function __construct()
    {
        $this->stateService = StateService::instance();
    }

    /**
     * 创建事物状态
     *
     * @param PostThingStateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThingState(PostThingStateRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $data = $request->only(['name', 'cond']);

            $state = $this->stateService->addState('123', $thingId, $data['name'], $data['cond']);

            if($state){
                return $this->successResponse();

            }else{
                return $this->failResponse('创建事物状态失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新事物状态
     *
     * @param PutThingStateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateThingState(PutThingStateRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $stateId = Route::input('stateId');

            $data = $request->only(['name', 'cond']);

            $state = $this->stateService->updateState('123', $stateId, $data['name'], $data['cond']);

            if($state){
                return $this->successResponse();

            }else{
                return $this->failResponse('创建事物状态失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除事物状态及其条件
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyThingState()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $stateId = Route::input('stateId');

            $result = $this->stateService->deleteState('123', $stateId);

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
     * 创建事物状态
     *
     * @param PostThingStateOperationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThingStateOperation(PostThingStateOperationRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $stateId = Route::input('stateId');

            $operationId = $request->input('operationId');

            $result = $this->stateService->addStateOperation('123', $stateId, $operationId);

            if($result){
                return $this->successResponse();

            }else{
                return $this->failResponse('绑定状态和操作失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除事物状态及其条件
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyThingStateOperation()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $stateId = Route::input('stateId');
            $operationId = Route::input('operationId');

            $result = $this->stateService->deleteStateOperation('123', $stateId, $operationId);

            if($result){
                return $this->successResponse();

            }else{
                return $this->failResponse('解除状态和操作失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
