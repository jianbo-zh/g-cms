<?php

namespace App\Http\Controllers\Thing;

use App\Services\Thing\Service\FieldService;
use Illuminate\Support\Facades\Route;


class FieldController extends Controller
{
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

        $this->fieldService = FieldService::instance();
    }

    /**
     * 事物字段列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexThingFields()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $fields = $this->fieldService->getFields('123', $thingId);

            return view('platform.thing.field.indexThingFields', [
                'appId' => $appId,
                'thingId' => $thingId,
                'fields' => $fields
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建事物字段表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createThingField()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $storageTypes = $this->fieldService->getFieldStorageTypes('123');
            $showTypes = $this->fieldService->getFieldShowTypes('123');


            return view('platform.thing.field.createThingField', [
                'appId' => $appId,
                'thingId' => $thingId,
                'storageTypes' => $storageTypes,
                'showTypes' => $showTypes,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑事物字段表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editThingField()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $fieldId = Route::input('fieldId');

            $this->setOperationContext($appId, $thingId);

            $storageTypes = $this->fieldService->getFieldStorageTypes('123');
            $showTypes = $this->fieldService->getFieldShowTypes('123');

            $field = $this->fieldService->getField('123', $fieldId);

            return view('platform.thing.field.editThingField', [
                'appId' => $appId,
                'thingId' => $thingId,
                'storageTypes' => $storageTypes,
                'showTypes' => $showTypes,
                'field' => $field,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
