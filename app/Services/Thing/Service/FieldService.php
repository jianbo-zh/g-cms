<?php

namespace App\Services\Thing\Service;

use App\Services\_Base\Service;
use App\Services\Thing\Model\FieldModel;
use App\Services\Thing\Repository\FieldRepository;
use Illuminate\Database\Eloquent\Builder;

class FieldService extends Service
{
    /**
     * @var FieldRepository
     */
    protected $fieldRepo;

    /**
     * FieldService constructor.
     */
    protected function __construct()
    {
        $this->fieldRepo = FieldRepository::instance();
    }

    /**
     * 获取事物所有字段
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return array 字段列表
     */
    public function getFields(string $authCode, int $thingId)
    {

        return $this->fieldRepo->getFields($thingId);
    }

    /**
     * 获取事物字段
     *
     * @param string $authCode 授权码
     * @param int $field 字段编号
     * @return array 事物字段
     */
    public function getField(string $authCode, int $field)
    {
        return $this->fieldRepo->getField($field);
    }

    /**
     * 向指定事物添加字段
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param string $name 字段名称
     * @param string $storageType 存储类型
     * @param string $showType 显示类型
     * @param array $showOption 显示选项
     * @param bool $isList 是否列表显示 (true:是, false:否)
     * @param bool $isSearch 是否搜索条件 (true:是, false:否)
     * @param string $comment 字段备注
     * @return mixed 新增的字段信息
     * @throws \App\Services\_Base\Exception
     */
    public function addField(string $authCode, int $thingId, string $name, string $comment, string $storageType,
                             string $showType, array $showOption, bool $isList, bool $isSearch)
    {

        return $this->fieldRepo->addField($thingId, $name, $comment, $storageType, $showType, $showOption,
            $isList, $isSearch);
    }

    /**
     * 更新一个事物的字段信息
     *
     * @param string $authCode 授权码
     * @param int $fieldId 字段编号
     * @param string $name 字段名称
     * @param string $storageType 存储类型 (tinyint,smallint,mediumint,float,double,decimal82,varchar10,varchar20,...)
     * @param string $showType 显示类型
     * @param array $showOption 显示选项
     * @param bool $isList 是否列表显示 (true:是, false:否)
     * @param bool $isSearch 是否搜索条件 (true:是, false:否)
     * @param string $comment 字段备注
     * @return mixed 新增的字段信息
     * @throws \App\Services\_Base\Exception
     */
    public function updateField(string $authCode, int $fieldId, ?string $name=null, ?string $comment=null,
                                ?string $storageType=null, ?string $showType=null, ?array $showOption=null,
                                ?bool $isList=null, ?bool $isSearch=null)
    {
        return $this->fieldRepo->updateField($fieldId, $name, $comment, $storageType, $showType, $showOption,
            $isList, $isSearch);
    }

    /**
     * 删除指定事物字段
     *
     * @param string $authCode 授权码
     * @param int $fieldId 字段编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteField(string $authCode, int $fieldId)
    {
        $result = $this->fieldRepo->deleteField($fieldId);
        return ($result !== false) ? true : false;
    }

    /**
     * 获取字段的所有存储类型
     *
     * @param string $authCode 授权码
     * @return array 存储类型列表 ['tinyint', 'smallint', 'mediumint', 'float', 'double', 'decimal8', ...]
     */
    public function getFieldStorageTypes(string $authCode)
    {
        return $this->fieldRepo->getFieldStorageTypes();
    }

    /**
     * 获取所有字段的显示类型
     *
     * @param string $authCode 授权码
     * @return array 显示类型列表
     */
    public function getFieldShowTypes(string $authCode)
    {
        return $this->fieldRepo->getFieldShowTypes();
    }

    /**
     * 获取指定操作的字段信息
     *
     * @param string $authCode 授权码
     * @param int $operationId 操作编号
     * @return array 字段信息集合
     */
    public function getFieldsOfOperation(string $authCode, int $operationId)
    {
        return $this->fieldRepo->getFieldsOfOperation($operationId, ['id', 'thing_id', 'name', 'show_type',
            'show_options', 'is_list', 'is_search', 'comment']);
    }
}