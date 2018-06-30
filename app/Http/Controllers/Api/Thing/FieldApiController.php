<?php

namespace App\Http\Controllers\Api\Thing;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Thing\PostThingFieldRequest;
use App\Http\Requests\Thing\PutThingFieldRequest;
use App\Services\Thing\Service\FieldService;
use Illuminate\Support\Facades\Route;

/**
 * 事物字段相关接口
 *
 * Class ThingApiController
 * @package App\Http\Controllers\Api
 */
class FieldApiController extends Controller
{
    /**
     * @var FieldService
     */
    protected $fieldService;

    /**
     * FieldApiController constructor.
     */
    public function __construct()
    {
        $this->fieldService = FieldService::instance();
    }

    /**
     * 创建事物字段
     *
     * @param PostThingFieldRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThingField(PostThingFieldRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $data = $request->only(['name', 'storageType', 'showType', 'showOptions', 'isList', 'isSearch', 'comment']);

            $data['isList'] = !empty($data['isList']) ? true : false;
            $data['isSearch'] = !empty($data['isSearch']) ? true : false;
            $data['showOptions'] = $data['showOptions'] ? json_decode($data['showOptions'], true) : [];


            $field = $this->fieldService->addField('123', $thingId, $data['name'], $data['comment'],
                $data['storageType'], $data['showType'], $data['showOptions'], $data['isList'], $data['isSearch']);

            if(! $field){
                throw new \Exception('创建字段失败！');
            }

            return $this->successResponse();

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新事物字段
     *
     * @param PutThingFieldRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateThingField(PutThingFieldRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $fieldId = Route::input('fieldId');

            $data = $request->only(['name', 'storageType', 'showType', 'showOptions', 'isList', 'isSearch', 'comment']);

            $data['isList'] = !empty($data['isList']) ? true : false;
            $data['isSearch'] = !empty($data['isSearch']) ? true : false;
            $data['showOptions'] = $data['showOptions'] ? json_decode($data['showOptions'], true) : [];

            $field = $this->fieldService->updateField('123', $fieldId, $data['name'], $data['comment'],
                $data['storageType'], $data['showType'], $data['showOptions'], $data['isList'], $data['isSearch']);

            if(! $field){
                throw new \Exception('更新字段失败！');
            }

            return $this->successResponse();

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除事物字段
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyThingField()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $fieldId = Route::input('fieldId');

            $result = $this->fieldService->deleteField('123', $fieldId);

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
