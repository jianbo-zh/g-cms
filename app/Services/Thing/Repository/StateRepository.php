<?php

namespace App\Services\Thing\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\Thing\Model\FieldModel;
use App\Services\Thing\Model\OperationModel;
use App\Services\Thing\Model\State2OperationModel;
use App\Services\Thing\Model\StateConditionModel;
use App\Services\Thing\Model\StateModel;
use Illuminate\Database\Eloquent\Builder;

class StateRepository extends Repository
{

    /**
     * 获取事物的所有状态
     *
     * @param int $thingId 事物编号
     * @param array $fields 返回字段
     * @return mixed 状态列表
     */
    public function getStates(int $thingId, array $fields=null)
    {
        $states = StateModel::where('thing_id', $thingId)
            ->when(!empty($fields), function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->get();

        return $this->normalizeReturn($states);
    }

    /**
     * 通过编号获取状态详情
     *
     * @param int $stateId 状态
     * @return mixed
     */
    public function getState(int $stateId)
    {
        $state = StateModel::find($stateId);

        return $this->normalizeReturn($state);
    }

    /**
     * 添加状态
     *
     * @param int $thingId 事物编号
     * @param string $name 名称
     * @return array 新增的状态
     */
    public function addState(int $thingId, string $name)
    {
        $state = StateModel::create([
            'thing_id'  => $thingId,
            'name'      => $name
        ]);

        return $this->normalizeReturn($state);
    }

    /**
     * 更新指定状态
     *
     * @param int $stateId 状态编号
     * @param string $name 名称
     * @return bool 是否成功
     */
    public function updateState(int $stateId, string $name)
    {
        $state = StateModel::findOrFail($stateId);
        $state->name = $name;
        $result = $state->save();
        return $result ? $this->normalizeReturn($state) : false;
    }

    /**
     * 删除指定状态
     *
     * @param int $stateId 状态编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteState(int $stateId)
    {
        $state = StateModel::findOrFail($stateId);

        return $state->delete();
    }

    /**
     * 获取状态的所有条件
     *
     * @param int $stateId 状态编号
     * @param array $fields 返回字段
     * @return mixed 状态条件列表
     */
    public function getStateConditions(int $stateId, array $fields=null)
    {
        $conditions = StateConditionModel::where('state_id', $stateId)
            ->when(!is_null($fields), function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->get();
        return $this->normalizeReturn($conditions);
    }

    /**
     * 获取事物的所有状态条件
     *
     * @param int $thingId 事物编号
     * @param array|null $fields 返回字段
     * @return array|mixed
     */
    public function getStateConditionsOfThing(int $thingId, array $fields=null)
    {
        $stateConditions = [];
        $states = StateModel::where('thing_id', $thingId)->select(['id'])->get();
        if(!empty($states)){
            $stateIds = [];
            foreach ($states as $state){
                $stateIds[] = $state->id;
            }

            $result = StateConditionModel::whereIn('state_id', $stateIds)
                ->when(!empty($fields), function(Builder $query) use ($fields){
                    return $query->select($fields);
                })
                ->get();

            $stateConditions = $this->normalizeReturn($result);
        }

        return $stateConditions;
    }

    /**
     * 增加状态条件
     *
     * @param int $stateId 状态编号
     * @param int $fieldId 字段编号
     * @param string $symbol 操作符号
     * @param string|null $value 操作值
     * @return array 新增的状态条件
     * @throws Exception
     */
    public function addStateCondition(int $stateId, int $fieldId, string $symbol, $value)
    {
        if(!in_array($symbol, array_keys(StateConditionModel::getSymbolMap()))){
            throw new Exception('操作符错误！');
        }

        StateModel::findOrFail($stateId);
        FieldModel::findOrFail($fieldId);

        $condition = StateConditionModel::create([
            'state_id' => $stateId,
            'field_id' => $fieldId,
            'symbol'    => $symbol,
            'value'    => $value,
        ]);

        return $this->normalizeReturn($condition);
    }

    /**
     * 更新状态条件信息
     *
     * @param int $conditionId 条件编号
     * @param int|null $stateId 状态编号
     * @param int|null $fieldId 字段编号
     * @param string|null $symbol 操作符号
     * @param string|null $value 操作值
     * @return array|false 成功返回新状态条件，失败返回false
     * @throws Exception
     */
    public function updateStateCondition(int $conditionId, ?int $stateId=null, ?int $fieldId=null, ?string $symbol=null,
                                         ?$value=null)
    {
        if(is_null($stateId) && is_null($fieldId) && is_null($symbol) && is_null($value)){
            throw new Exception('更新字段不能都为空！');
        }

        $condition = StateConditionModel::findOrFail($conditionId);

        if(!is_null($stateId)){
            if($condition['state_id'] != $stateId){
                StateModel::findOrFail($stateId);   // 检查是否存在
                $condition->state_id = $stateId;
            }
        }
        if(!is_null($fieldId)){
            if($condition['field_id'] != $fieldId){
                FieldModel::findOrFail($fieldId);   // 检查是否存在
                $condition->field_id = $fieldId;
            }
        }
        if(!is_null($symbol)){
            if($condition['symbol'] != $symbol){
                if(!in_array($symbol, array_keys(StateConditionModel::getSymbolMap()))){
                    throw new Exception('错误的操作符号！');
                }
                $condition->symbol = $symbol;
            }

        }
        if(!is_null($value)){
            if($condition['value'] != $value){
                $condition->value = $value;
            }
        }

        $result = $condition->save();
        return $result ? $this->normalizeReturn($condition) : false;
    }

    /**
     * 删除状态条件
     *
     * @param int $conditionId 条件编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteStateCondition(int $conditionId)
    {
        $condition = StateConditionModel::findOrFail($conditionId);

        return $condition->delete();
    }

    /**
     * 获取状态条件所有操作符号
     *
     * @return array
     */
    public function getStateConditionSymbols()
    {
        $symbols = StateConditionModel::getSymbolMap();

        return $symbols;
    }

    /**
     * 关联状态和操作
     *
     * @param int $stateId 状态编号
     * @param int $operationId 操作编号
     * @return array
     */
    public function bindStateAndOperation(int $stateId, int $operationId)
    {
        $state2Operation = State2OperationModel::create([
            'state_id'      => $stateId,
            'operation_id'  => $operationId
        ]);
        return $this->normalizeReturn($state2Operation);
    }

    /**
     * 解绑功能和操作
     *
     * @param int $stateId 状态编号
     * @param int $operationId 操作编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function unbindStateAndOperation(?int $stateId=null, ?int $operationId=null)
    {
        if(is_null($stateId) && is_null($operationId)){
            throw new Exception('状态和操作不能都为空！');
        }
        $result = State2OperationModel::when($stateId, function(Builder $query) use ($stateId){
                return $query->where('state_id', $stateId);
            })
            ->when($operationId, function(Builder $query) use ($operationId){
                return $query->where('operation_id', $operationId);
            })
            ->delete();

        return $result;
    }

    /**
     * 获取状态和操作关联，通过状态编号集合
     *
     * @param array $stateIds
     * @return mixed
     */
    public function getState2OperationByStateIds(array $stateIds)
    {
        $state2Operations = State2OperationModel::whereIn('state_id', $stateIds)
            ->select(['state_id', 'operation_id'])
            ->get();

        return $this->normalizeReturn($state2Operations);
    }
}