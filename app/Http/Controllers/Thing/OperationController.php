<?php

namespace App\Http\Controllers\Thing;

use App\Services\Thing\Service\FieldService;
use App\Services\Thing\Service\OperationService;
use Illuminate\Support\Facades\Route;


class OperationController extends Controller
{
    /**
     * @var OperationService
     */
    protected $operationService;

    /**
     * @var FieldService
     */
    protected $fieldService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->operationService = OperationService::instance();

        $this->fieldService = FieldService::instance();
    }

    /**
     * 事物操作列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexThingOperations()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $operations = $this->operationService->getOperations('123', $thingId);

            return view('platform.thing.operation.indexThingOperations', [
                'appId' => $appId,
                'thingId' => $thingId,
                'operations' => $operations
            ]);


        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建事物操作表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createThingOperation()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $fields = $this->fieldService->getFields('123', $thingId);
            $fieldOperationTypes = $this->operationService->getOperationFieldUpdateTypes('123');

            $operationTypes = $this->operationService->getOperationTypes('123');
            $operationForms = $this->operationService->getOperationForms('123');

            return view('platform.thing.operation.createThingOperation', [
                'appId' => $appId,
                'thingId' => $thingId,
                'fields' => $fields,
                'operationTypes' => $operationTypes,
                'operationForms' => $operationForms,
                'fieldOperationTypes' => $fieldOperationTypes
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑事物操作表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editThingOperation()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $operationId = Route::input('operationId');

            $this->setOperationContext($appId, $thingId);

            $fields = $this->fieldService->getFields('123', $thingId);
            $fieldOperationTypes = $this->operationService->getOperationFieldUpdateTypes('123');

            $operationTypes = $this->operationService->getOperationTypes('123');
            $operationForms = $this->operationService->getOperationForms('123');

            $operation = $this->operationService->getOperation('123', $operationId);
            $operationFields = [];
            foreach ($operation['fields'] as $value){
                $operationFields[$value['fieldId']] = [
                    'operationFieldId' => $value['id'],
                    'isShow' => $value['isShow'],
                    'updateType' => $value['updateType'],
                ];
            }
            foreach ($fields as $key => $value){
                if(!empty($operationFields[$value['id']])){
                    $fields[$key] = array_merge($value, $operationFields[$value['id']]);
                }
            }

            return view('platform.thing.operation.editThingOperation', [
                'appId' => $appId,
                'thingId' => $thingId,
                'fieldOperationTypes' => $fieldOperationTypes,
                'operationTypes' => $operationTypes,
                'operationForms' => $operationForms,
                'fields' => $fields,
                'operation' => $operation,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
