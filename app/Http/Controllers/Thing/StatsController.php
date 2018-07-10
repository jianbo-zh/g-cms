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

            $statsItems = $this->statsService->getStatsItems('123', $thingId);

            return view('platform.thing.stats.indexThingStatsItems', [
                'appId' => $appId,
                'thingId' => $thingId,
                'statsItems' => $statsItems
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 查看统计图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showThingStatsItem()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $statsItemId = Route::input('statsItemId');

            $this->setOperationContext($appId, $thingId);

            $statsItemData = $this->statsService->getStatsItemData('123', $statsItemId);

            $statsItemChartConfig = $this->statsService->getStatsItemChartConfig('123', $statsItemId);

            $dataSet = $this->buildChartDataSet($statsItemData);

            return view('platform.thing.stats.showThingStatsItem', [
                'appId' => $appId,
                'thingId' => $thingId,
                'dataSet' => $dataSet,
                'chartType' => $statsItemChartConfig['chart'],
                'chartOption' => $statsItemChartConfig['option'],
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 统计数据转换成图表可识别的格式
     *
     * @param array $statsItemData 统计数据
     * @return array 图表可识别数据
     * @throws \Exception
     */
    private function buildChartDataSet(array $statsItemData)
    {
        $columnNum = count($statsItemData[0]);
        if($columnNum > 3){
            throw new \Exception('超出范围！');

        }else if($columnNum < 3){
            return $statsItemData;
        }
        $titleHeads = [];
        $data = [];
        foreach ($statsItemData as $key => $value){
            if($key === 0){
                continue;
            }
            if(! in_array($value[1], $titleHeads)){
                $titleHeads[] = $value[1];
            }
            $data[$value[0]][$value[1]] = $value[2];
        }
        $newTitleHeads = $titleHeads;
        array_unshift($newTitleHeads, $statsItemData[0][0]);
        $dataSet = [
            $newTitleHeads
        ];
        foreach ($data as $group1Name => $group1){
            $tmp = [$group1Name];
            foreach ($titleHeads as $group2Name){
                $tmp[] = isset($group1[$group2Name]) ? $group1[$group2Name] : 0;
            }
            $dataSet[] = $tmp;
        }

        return $dataSet;
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

            $symbols = $this->stateService->getStateConditionSymbols('123');

            $state = $this->stateService->getState('123', $stateId);

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
