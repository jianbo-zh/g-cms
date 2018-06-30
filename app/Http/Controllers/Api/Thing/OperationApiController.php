<?php

namespace App\Http\Controllers\Api\Thing;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Thing\PostThingOperationRequest;
use App\Http\Requests\Thing\PutThingOperationRequest;
use App\Services\_Base\Exception;
use App\Services\Thing\Service\OperationService;
use Illuminate\Support\Facades\Route;

/**
 * 事物字段相关接口
 *
 * Class ThingApiController
 * @package App\Http\Controllers\Api
 */
class OperationApiController extends Controller
{
    /**
     * @var OperationService
     */
    protected $operationService;

    /**
     * OperationApiController constructor.
     */
    public function __construct()
    {
        $this->operationService = OperationService::instance();
    }

    /**
     * 创建事物操作
     *
     * @param PostThingOperationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThingOperation(PostThingOperationRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $data = $request->only(['name', 'operationType', 'operationForm', 'fields']);
            if(!is_array($data['fields'])){
                throw new \Exception('参数fields错误！');
            }
            foreach ($data['fields'] as $key => $field){
                $data['fields'][$key] = [
                    'fieldId'       => $field['fieldId'],
                    'isShow'        => !empty($field['isShow']) ? true : false,
                    'updateType'    => $field['updateType'],
                ];
            }

            $operation = $this->operationService->addOperation('123', $thingId, $data['name'],
                $data['operationType'], $data['operationForm'], $data['fields']);

            if($operation){
                return $this->successResponse($operation);

            }else{
                return $this->failResponse('创建操作失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新事物操作
     *
     * @param PutThingOperationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateThingOperation(PutThingOperationRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $operationId = Route::input('operationId');

            $data = $request->only(['name', 'operationType', 'operationForm', 'fields']);
            if(!is_array($data['fields'])){
                throw new \Exception('参数fields错误！');
            }
            foreach ($data['fields'] as $key => $field){
                $data['fields'][$key] = [
                    'id'            => $field['id'],
                    'fieldId'       => $field['fieldId'],
                    'isShow'        => !empty($field['isShow']) ? true : false,
                    'updateType' => $field['updateType'],
                ];
            }

            $operation = $this->operationService->updateOperation('123', $operationId, $data['name'],
                $data['operationType'], $data['operationForm'], $data['fields']);

            if($operation){
                return $this->successResponse();

            }else{
                return $this->failResponse('操作失败！');
            }


        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除事物操作
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyThingOperation()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $operationId = Route::input('operationId');

            $result = $this->operationService->deleteOperation('123', $operationId);

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
