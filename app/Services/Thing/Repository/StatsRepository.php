<?php

namespace App\Services\Thing\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\Thing\Model\FieldModel;
use App\Services\Thing\Model\StatsItemModel;
use App\Services\Thing\Model\ThingModel;
use Illuminate\Support\Facades\DB;

class StatsRepository extends Repository
{

    /**
     * 获取指定统计的统计数据
     * @param int $statsItemId 统计项编号
     * @return array 统计数据，含标题
     * @throws Exception
     * @throws \Exception
     */
    public function getStatsItemData(int $statsItemId)
    {
        $statsItem = StatsItemModel::findOrFail($statsItemId);

        $prevConds = $statsItem->data_config['cond'];
        $groups = $statsItem->data_config['group'];

        $thing = ThingModel::where('id', $statsItem['thing_id'])->first();

        $fieldIds = [];
        foreach ($prevConds as $prevCond){
            $fieldIds[] = $prevCond['fieldId'];
        }
        foreach ($groups as $group){
            $fieldIds[] = $group['fieldId'];
        }
        $fieldIds = array_unique($fieldIds);

        $fieldMap = FieldModel::whereIn('id', $fieldIds)->pluck('name', 'id');
        foreach ($prevConds as $key => $prevCond){
            $prevConds[$key]['fieldName'] = $fieldMap[$prevCond['fieldId']];
        }

        $query = \DB::table($thing->table_name);

        foreach ($prevConds as $condition){
            switch ($condition['symbol']){
                case 'EQ':
                    $query->where($condition['fieldName'], $condition['value']);
                    break;
                case 'NEQ':
                    $query->where($condition['fieldName'], '!=', $condition['value']);
                    break;
                case 'GT':
                    $query->where($condition['fieldName'], '>', $condition['value']);
                    break;
                case 'LT':
                    $query->where($condition['fieldName'], '<', $condition['value']);
                    break;
                case 'EGT':
                    $query->where($condition['fieldName'], '>=', $condition['value']);
                    break;
                case 'ELT':
                    $query->where($condition['fieldName'], '<=', $condition['value']);
                    break;
                case 'NULL':
                    $query->whereNull($condition['fieldName']);
                    break;
                case 'NOT NULL':
                    $query->whereNotNull($condition['fieldName']);
                    break;
                case 'BETWEEN':
                    $between = explode(',', $condition['value']);
                    if (count($between) != 2) {
                        throw new Exception("字段{$condition['fieldName']}配置错误！");
                    }
                    $min = (int)trim($between[0]);
                    $max = (int)trim($between[1]);
                    $query->whereBetween($condition['fieldName'], [$min, $max]);
                    break;
                case 'IN':
                    $ins = explode(',', $condition['value']);
                    if (count($ins) == 0) {
                        throw new Exception("字段{$condition['fieldName']}配置错误！");
                    }
                    $ins = array_map('trim', $ins);
                    $query->whereIn($condition['fieldName'], $ins);
                    break;
                case 'FIELD':
                    $otherKey = trim($condition['value']);
                    $query->whereColumn($condition['fieldName'], '=', $otherKey);
                    break;
            }
        }

        $contentIds = $query->pluck('id');

        // reset query
        $query = \DB::table($thing->table_name);
        $query->whereIn('id', $contentIds);

        $headTitle = [];

        if(count($groups) === 2){
            /*
             * 如果是两维数据
             */
            $group1 = $groups[0];
            $group2 = $groups[1];
            if($group1['type']==='timeGroup' && $group2['type']==='calculate'){
                /*
                 * 基于时间的分组统计
                 */
                switch ($group1['operation']){
                    case 'timeMinute':
                        $len = 16; break;
                    case 'timeHour':
                        $len = 13; break;
                    case 'timeDay':
                        $len = 10; break;
                    case 'timeMonth':
                        $len = 7; break;
                    case 'timeYear':
                        $len = 4; break;
                    default:
                        $len = 0; break;
                }

                if($len){
                    $groupByName1 = \DB::raw("left({$fieldMap[$group1['fieldId']]}, {$len})");
                    $fieldName1 = \DB::raw("left({$fieldMap[$group1['fieldId']]}, {$len}) as t");

                }else{
                    $groupByName1 = $fieldMap[$group1['fieldId']];
                    $fieldName1 = \DB::raw("{$fieldMap[$group1['fieldId']]} as t");
                }
                $headTitle['t'] = $group1['name'];

                if($group2['operation'] === 'calculateCount'){
                    $fieldName2 = \DB::raw('COUNT(*) as c');

                }else if($group2['operation'] === 'calculateSum'){
                    $fieldName2 = \DB::raw('SUM('. $fieldMap[$group2['fieldId']] .') as c');
                }else{
                    throw new Exception('错误！');
                }
                $headTitle['c'] = $group2['name'];

                $query->select(['time'=>$fieldName1, $fieldName2])->groupBy(DB::raw($groupByName1));



            }else if($group1['type']==='commonGroup' && $group2['type']==='calculate'){
                /*
                 * 基于普通分组的统计
                 */

                $fieldName1 = $fieldMap[$group1['fieldId']];

                if($group2['operation'] === 'calculateCount'){
                    $fieldName2 = \DB::raw('COUNT(*) as c');

                }else if($group2['operation'] === 'calculateSum'){

                    $fieldName2 = \DB::raw('SUM('. $fieldMap[$group2['fieldId']] .') as c');
                }else{
                    throw new Exception('错误！');
                }

                $headTitle[$fieldName1] = $group1['name'];
                $headTitle['c'] = $group2['name'];

                $query->select($fieldName1, $fieldName2)->groupBy($fieldMap[$group1['fieldId']]);

            }

        }else if(count($groups) === 3){
            /*
             * 如果是三维数据
             */
            $group1 = $groups[0];
            $group2 = $groups[1];
            $group3 = $groups[2];

            if($group1['type']==='timeGroup'){
                /*
                 * 第一维是基于时间的分组
                 */
                switch ($group1['operation']){
                    case 'timeMinute':
                        $len = 16; break;
                    case 'timeHour':
                        $len = 13; break;
                    case 'timeDay':
                        $len = 10; break;
                    case 'timeMonth':
                        $len = 7; break;
                    case 'timeYear':
                        $len = 4; break;
                    default:
                        $len = 0; break;
                }

                if($len){
                    $groupByName1 = \DB::raw("left({$fieldMap[$group1['fieldId']]}, {$len})");
                }else{
                    $groupByName1 = $fieldMap[$group1['fieldId']];
                }

                $fieldName1 = \DB::raw("{$groupByName1} as t");

                $headTitle['t'] = $group1['name'];

            }elseif($group1['type']==='commonGroup'){
                /*
                 * 第一维度是基于时间的分组
                 */
                $groupByName1 = $fieldMap[$group1['fieldId']];
                $fieldName1 = $groupByName1;

                $headTitle[$fieldName1] = $group1['name'];
            }

            if($group2['type'] !== 'commonGroup'){
                /*
                 * 第二维必须是基于普通的分组
                 */
                throw new Exception('第二维度必须是普通分组！');

            }else if($group3['type'] !== 'calculate'){
                /*
                 * 第三维必须是基于统计，计数或求和
                 */
                throw new Exception('第三维度必须是统计！');
            }

            $groupByName2 = $fieldMap[$group2['fieldId']];
            $fieldName2 = $groupByName2;

            $headTitle[$fieldName2] = $group2['name'];

            if($group3['operation'] === 'calculateCount'){
                $fieldName3 = \DB::raw('COUNT(*) as c');

            }else if($group3['operation'] === 'calculateSum'){
                $fieldName3 = \DB::raw('SUM('. $fieldMap[$group3['fieldId']] .') as c');

            }else{
                throw new Exception('错误！');
            }
            $headTitle['c'] = $group3['name'];

            $query->select($fieldName1, $fieldName2, $fieldName3)->groupBy($groupByName1, $groupByName2);

        }else{
            throw new \Exception('错误！');
        }

        $data = $query->get();
        $returnData[] = array_values($headTitle);
        foreach ($data as $obj){
            $returnData[] = array_values(json_decode(json_encode($obj), true));
        }

        return $returnData;
    }

    /**
     * 获取统计的图表配置
     *
     * @param int $statsItemId
     * @return mixed
     */
    public function getStatsItemChartConfig(int $statsItemId)
    {
        $statsItem = StatsItemModel::findOrFail($statsItemId);

        return $this->normalizeReturn($statsItem->chart_config);
    }

    /**
     * 获取事物的统计项配置列表
     *
     * @param int $thingId 事物编号
     * @param array $fields
     * @return mixed
     */
    public function getStatsItems(int $thingId, array $fields=['*'])
    {
        $statsItems = StatsItemModel::where('thing_id', $thingId)
            ->select($fields)
            ->get();

        return $this->normalizeReturn($statsItems);
    }

    /**
     * 创建事物统计
     * @param int $thingId 事物编号
     * @param string $name 统计名称
     * @param array $dataConfig SQL查询数据库数据相关配置
     * @param array $chartConfig 统计图表相关配置
     * @return mixed 成功返回新建的统计信息
     */
    public function addStatsItem(int $thingId, string $name, array $dataConfig, array $chartConfig)
    {
        $stats = StatsItemModel::create([
            'thing_id' => $thingId,
            'name' => $name,
            'data_config' => $dataConfig,
            'chart_config' => $chartConfig,
        ]);

        return $this->normalizeReturn($stats);
    }

    /**
     * 更新事物统计
     *
     * @param int $statsItemId 事物统计项编号
     * @param null|string $name 统计名称
     * @param array|null $dataConfig SQL查询数据库数据相关配置
     * @param array|null $chartConfig 统计图表相关配置
     * @return bool|mixed 是否成功，成功则返回更新后的统计信息，失败false
     * @throws Exception
     */
    public function updateStatsItem(int $statsItemId, ?string $name=null, ?array $dataConfig=null, ?array $chartConfig=null)
    {
        if(is_null($name) || is_null($dataConfig) || is_null($chartConfig)){
            throw new Exception('更新数据统计不能都为空！');
        }

        $stats = StatsItemModel::findOrFail($statsItemId);
        if(!is_null($name)){
            $stats->name = $name;
        }
        if(!is_null($dataConfig)){
            $stats->data_config = $dataConfig;
        }
        if(!is_null($chartConfig)){
            $stats->chart_config = $chartConfig;
        }

        $result = $stats->save();

        return $result ? $this->normalizeReturn($stats) : false;
    }

    /**
     * 删除指定的事物统计项
     *
     * @param int $statsItemId 事物统计编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteStatsItem(int $statsItemId)
    {
        $stats = StatsItemModel::findOrFail($statsItemId);

        return $stats->delete();
    }

}