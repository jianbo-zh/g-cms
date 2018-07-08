<?php

namespace App\Services\Thing\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\Thing\Model\OperationFieldModel;
use App\Services\Thing\Model\OperationModel;
use App\Services\Thing\Model\State2OperationModel;
use Illuminate\Database\Eloquent\Builder;

class OperationRepository extends Repository
{

    /**
     * 获取事物所有操作
     *
     * @param int $thingId 事物编号
     * @return array 操作列表
     */
    public function getOperations(int $thingId)
    {
        $operations = OperationModel::where('thing_id', $thingId)->get();

        return $this->normalizeReturn($operations);
    }

    /**
     * 通过ID编号集合获取操作集合
     *
     * @param array $operationIds ID编号集合
     * @param array $fields 返回字段
     * @return mixed
     */
    public function getOperationsByIds(array $operationIds, array $fields=[])
    {
        $operations = OperationModel::whereIN('id', $operationIds)
            ->when($fields, function(Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->get();

        return $this->normalizeReturn($operations);
    }

    /**
     * 获取操作详情
     *
     * @param int $operationId 操作编号
     * @return array 操作详情
     */
    public function getOperation(int $operationId)
    {
        $operation = OperationModel::findOrFail($operationId);

        return $this->normalizeReturn($operation);
    }

    /**
     * 添加一个操作
     *
     * @param int $thingId 事物编号
     * @param string $name 名称
     * @param string $operationType 操作类型
     * @param string $operationForm 操作形式
     * @return array 新增的操作
     * @throws Exception
     */
    public function addOperation(int $thingId, string $name, string $operationType, string $operationForm)
    {
        if(! in_array($operationType, array_keys(OperationModel::getOperationTypeMap()))){
            throw new Exception('操作类型错误！');
        }
        if(! in_array($operationForm, array_keys(OperationModel::getOperationFormMap()))){
            throw new Exception('操作形式错误！');
        }

        $operation = OperationModel::create([
            'thing_id' => $thingId,
            'name' => $name,
            'operation_type' => $operationType,
            'operation_form' => $operationForm,
        ]);

        return $this->normalizeReturn($operation);
    }

    /**
     * 更新操作
     *
     * @param int $operationId 操作编号
     * @param string $name 名称
     * @param string $operationType 操作类型
     * @param string $operationForm 操作形式
     * @return bool|array 成功返回新操作，失败返回false
     * @throws Exception
     */
    public function updateOperation(int $operationId, ?string $name=null, ?string $operationType=null,
                                    ?string $operationForm=null)
    {
        if(is_null($name) && is_null($operationType) && is_null($operationForm)){
            throw new Exception('更新时所有参数不能都为空！');
        }

        $operation = OperationModel::findOrFail($operationId);

        if(! is_null($name)){
            $operation->name = $name;
        }
        if(! is_null($operationType)){
            if(! in_array($operationType, array_keys(OperationModel::getOperationTypeMap()))){
                throw new Exception('操作类型错误！');
            }
            $operation->operation_type = $operationType;
        }

        if(! is_null($operationForm)){
            if(! in_array($operationForm, array_keys(OperationModel::getOperationFormMap()))){
                throw new Exception('操作形式值错误！');
            }
            $operation->operation_form = $operationForm;
        }

        $result = $operation->save();

        return $result ? $this->normalizeReturn($operation) : false;
    }

    /**
     * 删除操作
     *
     * @param int $operationId 操作编号
     * @return bool|null 是否删除成功
     * @throws \Exception
     */
    public function deleteOperation(int $operationId)
    {
        $operation = OperationModel::findOrFail($operationId);

        return $operation->delete();
    }

    /**
     * 获取操作字段列表
     *
     * @param int $operationId 操作编号
     * @param array $fields 返回字段
     * @return array 字段列表
     */
    public function getOperationFields(int $operationId, array $fields=null)
    {
        $fields = OperationFieldModel::where('operation_id', $operationId)
            ->when(!is_null($fields), function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->get();

        return $this->normalizeReturn($fields);
    }

    /**
     * 获取操作字段详情
     *
     * @param int $fieldId 字段编号
     * @return array 字段信息
     */
    public function getOperationField(int $fieldId)
    {
        $field = OperationFieldModel::find($fieldId);

        return $this->normalizeReturn($field);
    }

    /**
     * 添加操作的字段
     *
     * @param int $operationId 操作编号
     * @param int $thingFieldId 事物字段编号
     * @param bool $isShow 是否显示
     * @param string $updateType 操作值定义
     * @return array 操作的字段
     * @throws Exception
     */
    public function addOperationField(int $operationId, int $thingFieldId, bool $isShow, string $updateType)
    {
        if(!in_array($updateType, array_keys(OperationFieldModel::getUpdateTypeMap()))){
            throw new Exception('未定义的更新类型！');
        }

        $field = OperationFieldModel::create([
            'operation_id'      => $operationId,
            'field_id'          => $thingFieldId,
            'is_show'           => $isShow,
            'update_type'   => $updateType
        ]);

        return $this->normalizeReturn($field);
    }

    /**
     * 更新操作的字段
     *
     * @param int $operationFieldId 操作的字段编号
     * @param bool|null $isShow 是否显示
     * @param string|null $updateType 更新类型定义
     * @return bool|array
     * @throws Exception
     */
    public function updateOperationField(int $operationFieldId, ?bool $isShow=null, ?string $updateType=null)
    {
        if(is_null($isShow) && is_null($updateType)){
            throw new Exception('更新参数不能都为空！');
        }

        $field = OperationFieldModel::findOrFail($operationFieldId);
        if(!is_null($isShow)){
            $field->is_show = $isShow ? OperationFieldModel::IS_SHOW_YES : OperationFieldModel::IS_SHOW_NO;
        }

        if(!is_null($updateType)){
            if(!in_array($updateType, array_keys(OperationFieldModel::getUpdateTypeMap()))){
                throw new Exception('未定义的更新值类型！');
            }
            $field->update_type = $updateType;
        }

        $result = $field->save();

        return $result ? $this->normalizeReturn($field) : false;
    }

    /**
     * 删除操作字段
     *
     * @param int $operationFieldId 操作字段编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteOperationField(int $operationFieldId)
    {
        $field = OperationFieldModel::findOrFail($operationFieldId);

        return $field->delete();
    }

    /**
     * 获取字段可操作类型
     *
     * @return array
     */
    public function getOperationFieldUpdateTypes()
    {
        return OperationFieldModel::getUpdateTypeMap();
    }

    /**
     * 获取状态下所有的操作
     *
     * @param int $stateId 状态变化
     * @return array
     */
    public function getStateOperations(int $stateId)
    {
        $state2Operation = State2OperationModel::where('state_id', $stateId)->get();
        if(empty($state2Operation)){
            return [];
        }

        $operationIds = [];
        foreach ($state2Operation as $value){
            $operationIds[] = $value['operation_id'];
        }
        $operations = OperationModel::whereIn('id', $operationIds)->get();

        return $this->normalizeReturn($operations);
    }

    /**
     * 获取事物的所有操作编号
     *
     * @param $thingId
     * @return array
     */
    public function getOperationIdsOfThing($thingId)
    {
        $operationIds = [];
        $operations = OperationModel::where('thing_id', $thingId)->select('id')->get();
        foreach ($operations as $value){
            $operationIds[] = $value['id'];
        }

        return $operationIds;
    }

    /**
     * 获取状态的所有操作编号
     *
     * @param $stateId
     * @return array
     */
    public function getOperationIdsOfState($stateId)
    {
        $operationIds = [];
        $state2Operation = State2OperationModel::where('state_id', $stateId)->get();
        foreach ($state2Operation as $value){
            $operationIds[] = $value['operation_id'];
        }

        return $operationIds;
    }

    /**
     * 获取所有的操作类型
     *
     * @return array
     */
    public function getOperationTypes()
    {
        return OperationModel::getOperationTypeMap();
    }

    /**
     * 获取所有操作形式
     *
     * @return array
     */
    public function getOperationForms()
    {
        return OperationModel::getOperationFormMap();
    }

    /**
     * 解码事物权限码
     *
     * @param string $permCode 事物权限码
     * @return array|bool
     */
    public function decodePermCode(string $permCode)
    {
        return OperationModel::decodePermCode($permCode);
    }
}