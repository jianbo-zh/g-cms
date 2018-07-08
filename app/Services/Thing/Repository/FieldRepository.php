<?php

namespace App\Services\Thing\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\Thing\Model\FieldModel;
use App\Services\Thing\Model\OperationFieldModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FieldRepository extends Repository
{

    /**
     * 获取事物的所有字段
     *
     * @param int $thingId 事物编号
     * @param int $state 字段状态
     * @return mixed
     */
    public function getFields(int $thingId, int $state=null)
    {
        $fields = FieldModel::where('thing_id', $thingId)
            ->when($state, function (Builder $query) use ($state){
                return $query->where('state', $state);
            })
            ->get();

        return $this->normalizeReturn($fields);
    }

    /**
     * 通过编号集获取事物字段集
     *
     * @param array $fieldIds 事物字段编号集
     * @param array|null $fields 返回字段 [id, name, comment, storage_type, show_type, show_options, is_list, is_search, state]
     * @return array 事物字段集
     */
    public function getFieldsByIds(array $fieldIds, array $fields=null)
    {
        $fields = FieldModel::whereIn('id', $fieldIds)
            ->when(is_array($fields), function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->get();

        return $this->normalizeReturn($fields);
    }

    /**
     * 获取事物字段
     *
     * @param int $fieldId 字段编号
     * @return array 字段信息
     */
    public function getField(int $fieldId)
    {
        $field = FieldModel::find($fieldId);

        return $this->normalizeReturn($field);
    }

    /**
     * 添加一个事物字段
     *
     * @param int $thingId 事物编号
     * @param string $name 字段名称
     * @param string $comment 字段备注
     * @param string $storageType 存储类型
     * @param string $showType 显示类型
     * @param array $showOption 显示选项
     * @param bool $isList 是否列表显示 (true:是, false:否)
     * @param bool $isSearch 是否搜索条件 (true:是, false:否)
     * @return mixed 新增的字段信息
     * @throws Exception
     */
    public function addField(int $thingId, string $name, string $comment, string $storageType, string $showType,
                             array $showOption, bool $isList, bool $isSearch)
    {
        if(!in_array($storageType, array_keys(FieldModel::getStorageTypes()))){
            throw new Exception('存储类型错误！');
        }
        if(!in_array($showType, array_keys(FieldModel::getShowTypes()))){
            throw new Exception('显示类型错误！');
        }

        $field = FieldModel::create([
            'thing_id'      => $thingId,
            'name'          => $name,
            'name_old'      => '',
            'storage_type'  => $storageType,
            'show_type'     => $showType,
            'show_options'   => $showOption,
            'is_list'       => $isList,
            'is_search'     => $isSearch,
            'comment'       => $comment,
            'state'         => FieldModel::STATE_ADD
        ]);

        return $this->normalizeReturn($field);
    }


    /**
     * 更新一个事物字段
     *
     * @param int $fieldId 字段编号
     * @param string $name 字段名称
     * @param string $comment 字段备注
     * @param string $storageType 存储类型
     * @param string $showType 存储类型
     * @param array $showOption 显示值类型
     * @param bool $isList 是否列表显示 (true:是, false:否)
     * @param bool $isSearch 是否搜索条件 (true:是, false:否)
     * @return mixed 新增的字段信息
     * @throws Exception
     */
    public function updateField(int $fieldId, ?string $name=null, ?string $comment=null, ?string $storageType=null,
                                ?string $showType=null, ?array $showOption=null, ?bool $isList=null,
                                ?bool $isSearch=null)
    {
        if(is_null($name) && is_null($storageType) && is_null($comment) && is_null($isList) && is_null($isSearch) &&
            is_null($showType) && is_null($showOption)){
            throw new Exception('所有修改项目都不能为空！');
        }

        if(!is_null($storageType) && !in_array($storageType, array_keys(FieldModel::getStorageTypes()))){
            throw new Exception('存储类型错误！');
        }
        if(!is_null($showType) && !in_array($showType, array_keys(FieldModel::getShowTypes()))){
            throw new Exception('显示类型错误！');
        }

        $field = FieldModel::findOrFail($fieldId);

        if(!is_null($name)){
            $field->name_old = $field->name;
            $field->name = $name;
        }
        if(!is_null($comment)){
            $field->comment = $comment;
        }
        if(!is_null($storageType)){
            $field->storage_type = $storageType;
        }

        // 如果更新了表结构，则标记需要执行同步
        if( !(is_null($name) && is_null($storageType) && is_null($comment))){
            $field->state = FieldModel::STATE_UPDATE;
        }

        if(!is_null($showType)){
            $field->show_type = $showType;
        }
        if(!is_null($showOption)){
            $field->show_options = $showOption;
        }
        if(!is_null($isList)){
            $field->is_list = $isList ? FieldModel::IS_LIST_YES : FieldModel::IS_LIST_NO;
        }
        if(!is_null($isSearch)){
            $field->is_search = $isSearch ? FieldModel::IS_SEARCH_YES : FieldModel::IS_SEARCH_NO;
        }

        $result = $field->save();

        return $result ? $this->normalizeReturn($field) : false;
    }

    /**
     * 删除指定字段
     *
     * @param int $fieldId 字段编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteField(int $fieldId)
    {
        $field = FieldModel::findOrFail($fieldId);

        return $field->delete();
    }

    /**
     * 获取字段的存储类型
     *
     * @return array 存储类型列表
     */
    public function getFieldStorageTypes()
    {
        return FieldModel::getStorageTypes();
    }

    /**
     * 获取字段的显示类型
     *
     * @return array 存储类型列表
     */
    public function getFieldShowTypes()
    {
        return FieldModel::getShowTypes();
    }

    /**
     * 获取无更新操作字段类型
     *
     * @return string
     */
    public function getOperationFieldUpdateTypeNotUpdate()
    {
        return OperationFieldModel::UPDATE_TYPE_NOT_UPDATE;
    }

    /**
     * 获取用户更新操作字段类型
     *
     * @return string
     */
    public function getOperationFieldUpdateTypeUserInput()
    {
        return OperationFieldModel::UPDATE_TYPE_USER_INPUT;
    }

    /**
     * 获取指定操作的字段信息
     *
     * @param int $operationId 操作编号
     * @param array|null $fields 返回字段
     * @return array 字段信息集合
     */
    public function getOperationFieldsOfOperation(int $operationId, array $fields=null)
    {

        $operationFields = OperationFieldModel::where('operation_id', $operationId)
            ->when(!empty($fields), function(Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->get();


        return $this->normalizeReturn($operationFields);
    }

    /**
     * 获取指定操作的字段信息
     *
     * @param int $operationId 操作编号
     * @param array|null $fields 返回字段
     * @return array 字段信息集合
     */
    public function getFieldsOfOperation(int $operationId, array $fields=null)
    {
        $fieldsArr = [];
        $operationFields = OperationFieldModel::where('operation_id', $operationId)->get();
        if(! empty($operationFields)){
            $fieldIds = $updateTypeMap = [];
            foreach ($operationFields as $value){
                $fieldIds[] = $value->field_id;
                $updateTypeMap[$value->field_id] = $value->update_type;
            }
            $fields = FieldModel::whereIn('id', $fieldIds)
                ->when(!empty($fields), function (Builder $query) use ($fields){
                    return $query->select($fields);
                })
                ->get();
            foreach ($fields as $value){
                $field = $value->toArray();
                $field['update_type'] = $updateTypeMap[$value->id];
                $fieldsArr[] = $field;
            }
        }

        return $this->normalizeReturn($fieldsArr);
    }
}