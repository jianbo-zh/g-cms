<?php

namespace App\Http\Controllers\Api\Thing;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Thing\PostThingStateOperationRequest;
use App\Http\Requests\Thing\PostThingStateRequest;
use App\Http\Requests\Thing\PutThingStateRequest;
use App\Http\Requests\Thing\Stats\PostThingStatsRequest;
use App\Services\Thing\Service\StateService;
use App\Services\Thing\Service\StatsService;
use Illuminate\Support\Facades\Route;

/**
 * 事物统计相关接口
 *
 * Class ThingApiController
 * @package App\Http\Controllers\Api
 */
class StatsApiController extends Controller
{
    /**
     * @var StatsService
     */
    protected $statsService;

    /**
     * StateApiController constructor.
     */
    public function __construct()
    {
        $this->statsService = StatsService::instance();
    }

    /**
     * 创建事物统计
     *
     * @param PostThingStatsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThingStatsItem(PostThingStatsRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $data = $request->only(['name', 'cond', 'group', 'chartType', 'chartValue', 'chartOption']);

            $dataConfig = [
                'cond' => $data['cond'],
                'group' => $data['group'],
            ];

            $chartConfig = [
                'chart' => $data['chartValue'],
                'option' => json_decode($data['chartOption'], true)
            ];

            $stats = $this->statsService->addStatsItem('123', $thingId, $data['name'], $dataConfig,
                $chartConfig);

            if($stats){
                return $this->successResponse();

            }else{
                return $this->failResponse('创建事物统计失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新事物统计
     *
     * @param PutThingStateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateThingStatsItem(PutThingStateRequest $request)
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
     * 删除事物统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyThingStatsItem()
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

}
