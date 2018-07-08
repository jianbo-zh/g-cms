<?php

namespace App\Services\Thing\Logic;

use App\Services\_Base\Exception;
use App\Services\_Base\Logic;
use App\Services\Thing\Model\FieldModel;
use App\Services\Thing\Model\ThingModel;
use App\Services\Thing\Repository\FieldRepository;
use App\Services\Thing\Repository\FunctionRepository;
use App\Services\Thing\Repository\OperationRepository;
use App\Services\Thing\Repository\ThingRepository;
use App\Services\Thing\Service\OperationService;
use Illuminate\Support\Facades\DB;

class ThingLogic extends Logic
{
    /**
     * @var ThingRepository
     */
    protected $thingRepo;

    /**
     * @var FieldRepository
     */
    protected $fieldRepo;

    /**
     * @var OperationRepository
     */
    protected $operationRepo;

    /**
     * ThingLogic constructor.
     */
    protected function __construct()
    {
        $this->thingRepo = ThingRepository::instance();
        $this->fieldRepo = FieldRepository::instance();
        $this->operationRepo = OperationRepository::instance();
    }

    /**
     * 创建事物
     *
     * @param int $appId 应用编号
     * @param string $name 事物名称
     * @param string $dec 描述
     * @return array 事物
     * @throws \Exception
     */
    public function addThing(int $appId, string $name, string $dec)
    {
        $thing = $this->thingRepo->addThing($appId, $name, $dec);

        return $thing;
    }

    /**
     * 添加事物内容
     *
     * @param int $thingId 事物编号
     * @param int $operationId 操作编号
     * @param array $contentData 用户提交数据
     * @param array $extraData 用户当前环境数据
     * @return array|false 是否成功，成功返回内容信息，失败返回false
     * @throws \Exception
     */
    public function addThingContent(int $thingId, int $operationId, array $contentData, array $extraData)
    {
        try{
            DB::beginTransaction();

            $addData = [];
            $fields = $this->fieldRepo->getFieldsOfOperation($operationId);
            foreach ($fields as $field){
                if($field['updateType'] === $this->fieldRepo->getOperationFieldUpdateTypeUserInput()){
                    switch ($field['showType']){
                        case 'select':
                        case 'radio':
                            if(empty($field['showOptions'])){
                                throw new Exception("字段{$field['name']}配置错误！");
                            }
                            if(!isset($contentData[$field['name']])){
                                throw new \Exception("参数{$field['name']}缺失！");
                            }
                            $inOption = false;
                            foreach ($field['showOptions'] as $value){
                                if((string)$contentData[$field['name']] === (string)$value['value']){
                                    $inOption = true;
                                }
                            }
                            if(! $inOption){
                                throw new Exception("参数{$field['name']}错误，未在配置项内！");
                            }
                            $addData[$field['name']] = $contentData[$field['name']];
                            break;
                        case 'checkbox':
                            $addData[$field['name']] = !empty($contentData[$field['name']]) ? '1' : '0';
                            break;
                        case 'input':
                        case 'textarea':
                        default:
                            if(!isset($contentData[$field['name']])){
                                throw new \Exception("参数{$field['name']}缺失！");
                            }
                        $addData[$field['name']] = $contentData[$field['name']];
                            break;
                    }

                }else{
                    if($field['updateType'] !== $this->fieldRepo->getOperationFieldUpdateTypeNotUpdate()){
                        if(! isset($extraData[$field['updateType']])){     // 系统定义的更新类型
                            throw new Exception("未找到系统定义的{$field['updateType']}数据！");
                        }
                        $addData[$field['name']] = $extraData[$field['updateType']];
                    }
                }
            }

            $content = $this->thingRepo->addThingContent($thingId, $addData);

            DB::commit();

            return $content;

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 更新事物内容
     *
     * @param int $thingId 事物编号
     * @param int $operationId 操作编号
     * @param int $contentId 内容编号
     * @param array $contentData 用户输入内容
     * @param array $extraData 环境数据
     * @return array|bool 是否成功，成功则返回更新后的内容，失败返回false
     * @throws \Exception
     */
    public function updateThingContent(int $thingId, int $operationId, int $contentId, array $contentData,
                                       array $extraData)
    {
        try{
            DB::beginTransaction();

            $updateData = [];
            $fields = $this->fieldRepo->getFieldsOfOperation($operationId);
            foreach ($fields as $field){
                if($field['updateType'] === $this->fieldRepo->getOperationFieldUpdateTypeUserInput()){
                    switch ($field['showType']){
                        case 'select':
                        case 'radio':
                            if(empty($field['showOptions'])){
                                throw new Exception("字段{$field['name']}配置错误！");
                            }
                            if(!isset($contentData[$field['name']])){
                                throw new \Exception("参数{$field['name']}缺失！");
                            }
                            $inOption = false;
                            foreach ($field['showOptions'] as $value){
                                if((string)$contentData[$field['name']] === (string)$value['value']){
                                    $inOption = true;
                                }
                            }
                            if(! $inOption){
                                throw new Exception("参数{$field['name']}错误，未在配置项内！");
                            }
                            $updateData[$field['name']] = $contentData[$field['name']];
                            break;
                        case 'checkbox':
                            $updateData[$field['name']] = !empty($contentData[$field['name']]) ? '1' : '0';
                            break;
                        case 'input':
                        case 'textarea':
                        default:
                            if(!isset($contentData[$field['name']])){
                                throw new \Exception("参数{$field['name']}缺失！");
                            }
                            $updateData[$field['name']] = $contentData[$field['name']];
                            break;
                    }

                }else{
                    if($field['updateType'] !== $this->fieldRepo->getOperationFieldUpdateTypeNotUpdate()){
                        if(! isset($extraData[$field['updateType']])){     // 系统定义的更新类型
                            throw new Exception("未找到系统定义的{$field['updateType']}数据！");
                        }
                        $updateData[$field['name']] = $extraData[$field['updateType']];
                    }
                }
            }

            $content = $this->thingRepo->updateThingContent($thingId, $contentId, $updateData);

            DB::commit();

            return $content;

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 删除事物 （软删除）
     *
     * @param int $thingId 事物编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteThing(int $thingId)
    {
        $result = $this->thingRepo->deleteThing($thingId);
        return $result;
    }

    /**
     * 执行事物的所有字段迁移
     *
     * @param int $thingId 事物编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function migrateThing(int $thingId)
    {

        $thing = $this->thingRepo->getThing($thingId);

        if(! $thing){
            throw new Exception('未找到事物！');
        }

        $db = env('DB_DATABASE');
        $thingTableName = ThingModel::THING_MODEL_PREFIX . "{$thing['appId']}_{$thingId}";

        // TODO: 此处有依赖于MySQL数据库
        $existsThingTables = DB::select("SELECT `TABLE_NAME` FROM `INFORMATION_SCHEMA`.`TABLES` ".
            " WHERE `TABLE_SCHEMA` = '{$db}' AND TABLE_NAME = '{$thingTableName}'");

        $isTableExist = !empty($existsThingTables) ? true : false;

        $fields = $this->fieldRepo->getFields($thingId);


        try{
            DB::beginTransaction();

            if($isTableExist){
                // 修改表结构
                $sql = $this->buildExistThingMigrateSql($thingTableName, $thing['name'], $fields);
                $result = DB::statement($sql);
                if(! $result){
                    throw new Exception('更新迁移失败！');
                }

                // 更新事物字段状态
                $this->thingRepo->setThingMigrated($thingId);

            }else{
                // 修改表结构
                $sql = $this->buildNewThingMigrateSql($thingTableName, $thing['name'], $fields);
                $result = DB::statement($sql);
                if(! $result){
                    throw new Exception('更新迁移失败！');
                }

                // 更新事物表名
                $result = $this->thingRepo->updateThingTableName($thingId, $thingTableName);
                if(! $result){
                    throw new Exception('更新事物表名失败！');
                }

                // 更新事物字段状态
                $this->thingRepo->setThingMigrated($thingId);
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return true;
    }

    /**
     * 构造全新的事物迁移SQL
     *
     * @param string $tableName 事物表名
     * @param string $tableComment 事物备注
     * @param array $fields 事物字段信息
     * @return string 迁移SQL
     * @throws Exception
     */
    private function buildNewThingMigrateSql(string $tableName, string $tableComment, array $fields)
    {
        $storageTypeMap = $this->fieldRepo->getFieldStorageTypes();

        $aFields = [];

        foreach ($fields as $field){
            if(!in_array($field['storageType'], array_keys($storageTypeMap))){
                throw new Exception('未定义的存储类型！');
            }
            $aFields[] = "`{$field['name']}` {$storageTypeMap[$field['storageType']]} COMMENT '{$field['comment']}'";
        }

        if(empty($aFields)){
            throw new Exception('新表字段不能为空！');
        }

        $aFieldsSql = implode(', ', $aFields);

        $sql = <<<EOF
CREATE TABLE `{$tableName}` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	{$aFieldsSql},
	PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci' COMMENT='{$tableComment}';
EOF;

        return $sql;
    }

    /**
     * 构造一个需要更新的字段SQL
     *
     * @param string $tableName 事物表名
     * @param string $tableComment 事物备注
     * @param array $fields 事物字段
     * @return string 更新SQL
     * @throws Exception
     */
    private function buildExistThingMigrateSql(string $tableName, string $tableComment, array $fields)
    {
        $storageTypeMap = $this->fieldRepo->getFieldStorageTypes();

        $uFields = [];
        foreach ($fields as $field){

            if(!in_array($field['storageType'], array_keys($storageTypeMap))){
                throw new Exception('未定义的存储类型！');
            }

            $storageType = $storageTypeMap[$field['storageType']];

            switch ($field['state']){
                case FieldModel::STATE_ADD:
                    $uFields[] = "ADD `{$field['name']}` {$storageType} COMMENT '{$field['comment']}'";
                    break;
                case FieldModel::STATE_UPDATE:
                    if($field['nameOld']){
                        $uFields[] = "CHANGE `{$field['nameOld']}` `{$field['name']}` {$storageType} ".
                            "COMMENT '{$field['comment']}'";
                    }else{
                        $uFields[] = "MODIFY `{$field['name']}` {$storageType} COMMENT '{$field['comment']}'";
                    }
                    break;
                case FieldModel::STATE_MIGRATED:
                    // 已经迁移了不需要处理
                    break;
                default:
                    throw new Exception('错误的字段状态！');
            }
        }
        if(empty($uFields)){
            throw new Exception('没有需要更新的表字段！');
        }

        $uFieldsSql = implode(', ', $uFields);

        $sql = "ALTER TABLE `$tableName` {$uFieldsSql}, COMMENT '{$tableComment}';";

        return $sql;
    }

}