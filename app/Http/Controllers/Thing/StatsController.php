<?php

namespace App\Http\Controllers\Thing;

use App\Services\Thing\Service\FieldService;
use App\Services\Thing\Service\StateService;
use App\Services\Thing\Service\StatsService;
use Illuminate\Support\Facades\Route;

/**
 * 事物统计
 *
 * Class StatsController
 * @package App\Http\Controllers\Thing
 */
class StatsController extends Controller
{
    /**
     * @var StatsService
     */
    protected $statsService;

    /**
     * @var FieldService
     */
    protected $fieldService;

    /**
     * @var StateService
     */
    protected $stateService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->statsService = StatsService::instance();
        $this->fieldService = FieldService::instance();
        $this->stateService = StateService::instance();
    }

    /**
     * 事物统计列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexThingStatsItems()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $stateItems = [];

            return view('platform.thing.stats.indexThingStatsItems', [
                'appId' => $appId,
                'thingId' => $thingId,
                'stateItems' => $stateItems
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建事物统计表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createThingStatsItem()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);
            $fields = $this->fieldService->getFields('123', $thingId);
            $symbols = $this->stateService->getStateConditionSymbols('123');

            $groupTypes = [
                'timeGroup' => [
                    'name' => '时间分组',
                    'subs' => [
                        ['value'=>'timeMinute', 'name'=>'按分钟'],
                        ['value'=>'timeHour', 'name'=>'按小时'],
                        ['value'=>'timeDay', 'name'=>'按天'],
                        ['value'=>'timeMonth', 'name'=>'按月'],
                        ['value'=>'timeYear', 'name'=>'按年'],
                    ]
                ],
                'commonGroup' => [
                    'name' => '普通分组',
                    'subs' => [
                        ['value'=>'commonNormal', 'name'=>'自动'],
                    ]
                ],
                'calculate' => [
                    'name' => '分组计算',
                    'subs' => [
                        ['value'=>'calculateCount', 'name'=>'计数'],
                        ['value'=>'calculateSum', 'name'=>'求和'],
                    ]
                ],
            ];

            return view('platform.thing.stats.createThingStatsItem', [
                'appId' => $appId,
                'thingId' => $thingId,
                'fields' => $fields,
                'symbols' => $symbols,
                'groupTypes' => $groupTypes,
                'groupTypeMapJson' => json_encode($groupTypes),

            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑事物统计表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editThingStatsItem()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $stateId = Route::input('stateId');

            $this->setOperationContext($appId, $thingId);

            $fields = $this->fieldService->getFields('123', $thingId);

            $symbols = $this->statsService->getStateConditionSymbols('123');

            $state = $this->statsService->getState('123', $stateId);

            return view('platform.thing.stats.editThingStatsItem', [
                'appId'     => $appId,
                'thingId'   => $thingId,
                'fields'    => $fields,
                'symbols'   => $symbols,
                'state'     => $state
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
