<?php

namespace App\Services\Thing\Service;

use App\Services\_Base\Service;
use App\Services\Thing\Logic\OperationLogic;
use App\Services\Thing\Repository\FieldRepository;
use App\Services\Thing\Repository\OperationRepository;
use App\Services\Thing\Repository\StateRepository;
use App\Services\Thing\Repository\ThingRepository;
use App\Services\User\Service\RoleService;

class OperationService extends Service
{

    /**
     * @var OperationRepository
     */
    protected $operationRepo;

    /**
     * @var FieldRepository
     */
    protected $fieldRepo;

    /**
     * @var StateRepository
     */
    protected $stateRepo;

    /**
     * @var ThingRepository
     */
    protected $thingRepo;

    /**
     * @var OperationLogic
     */
    protected $operationLogic;

    /**
     * OperationService constructor.
     */
    protected function __construct()
    {
        $this->operationRepo = OperationRepository::instance();
        $this->fieldRepo = FieldRepository::instance();
        $this->stateRepo = StateRepository::instance();
        $this->thingRepo = ThingRepository::instance();
        $this->operationLogic = OperationLogic::instance();
    }

    /**
     * 获取事物操作列表
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return array 操作列表
     */
    public function getOperations(string $authCode, int $thingId)
    {
        $operations = $this->operationRepo->getOperations($thingId);

        return $operations;
    }

    /**
     * 获取事物操作详情
     *
     * @param string $authCode 授权码
     * @param int $operationId 操作编号
     * @return array 操作详情
     */
    public function getOperation(string $authCode, int $operationId)
    {
        $operation = $this->operationRepo->getOperation($operationId);

        $operation['fields'] = $this->operationRepo->getOperationFields($operationId);

        $fieldIds = [];

        foreach ($operation['fields'] as $value){
            $fieldIds[] = $value['fieldId'];
        }

        if(!empty($fieldIds)){
            $fields = $this->fieldRepo->getFieldsByIds($fieldIds, ['id', 'name', 'comment']);
            $fieldIdMap = [];
            foreach ($fields as $field){
                $fieldIdMap[$field['id']] = $field;
            }

            foreach ($operation['fields'] as $key => $value){
                $fieldId = $value['fieldId'];
                if(!empty($fieldIdMap[$fieldId])){
                    $operation['fields'][$key]['comment'] = $fieldIdMap[$fieldId]['comment'];
                }
            }
        }

        return $operation;
    }

    /**
     * 添加操作
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param string $name 操作名
     * @param string $operationType 操作类型 [add, delete, update, select]
     * @param string $operationForm 操作形式 [command, form]
     * @param array $operationFields 操作字段定义 [['fieldId'=>12, 'isShow'=>true, 'operationType'=>'current_user'],...]
     * @return array 新操作
     * @throws \Exception
     */
    public function addOperation(string $authCode, int $thingId,  string $name, string $operationType,
                                 string $operationForm, array $operationFields)
    {
        $operation = $this->operationLogic->addOperation($thingId, $name, $operationType, $operationForm,
            $operationFields);

        return $operation;
    }

    /**
     * 更新操作
     *
     * @param string $authCode 授权码
     * @param int $operationId 操作编号
     * @param string $name 操作名
     * @param string $operationType 操作类型 [add, delete, update, select]
     * @param string $operationForm 操作形式 [command, form]
     * @param array $operationFields 操作字段定义
     * @return array 更新后的操作
     * @throws \Exception
     */
    public function updateOperation(string $authCode, int $operationId, ?string $name=null, ?string $operationType=null,
                                    ?string $operationForm=null, ?array $operationFields=null)
    {
        $operation = $this->operationLogic->updateOperation($operationId, $name, $operationType, $operationForm,
            $operationFields);

        return $operation;
    }

    /**
     * 删除操作
     *
     * @param string $authCode 授权码
     * @param int $operationId 操作编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteOperation(string $authCode, int $operationId)
    {
        $result = $this->operationLogic->deleteOperation($operationId);

        return $result;
    }

    /**
     * 获取指定状态下所有的操作
     *
     * @param string $authCode 授权码
     * @param int $stateId 状态变化
     * @return array
     */
    public function getStateOperations(string $authCode, int $stateId)
    {
        $operations = $this->operationRepo->getStateOperations($stateId);

        return $operations;
    }

    /**
     * 获取应用角色
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $thingId 事物编号
     * @param int $userId 角色编号
     * @return array 角色信息
     */
    public function getOperationsOfUser(string $authCode, int $appId, int $thingId, int $userId)
    {
        $operations = [];
        $perms = $this->requestGetAppPermsOfUser($appId, $userId);
        if(!empty($perms)){
            $operationIds = [];
            foreach ($perms as $perm){
                if($permArr = $this->operationRepo->decodePermCode($perm)){
                    if($permArr['thingId'] == $thingId){
                        $operationIds[] = $permArr['operationId'];
                    }
                }
            }
            if(!empty($operationIds)){
                $operations = $this->operationRepo->getOperationsByIds($operationIds);
            }
        }

        return $operations;
    }

    /**
     * 获取用户拥有的事物操作编号集合
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param int $thingId 事物编号
     * @param int $userId 用户编号
     * @return array
     */
    public function getOperationIdsOfUser(string $authCode, int $appId, int $thingId, int $userId)
    {
        $operationIds = [];
        $perms = $this->requestGetAppPermsOfUser($appId, $userId);
        if(!empty($perms)){
            foreach ($perms as $perm){
                if($permArr = $this->operationRepo->decodePermCode($perm)){
                    if($permArr['thingId'] == $thingId){
                        $operationIds[] = $permArr['operationId'];
                    }
                }
            }
        }

        return $operationIds;
    }

    /**
     * 获取不属于某个状态的所有操作
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param int $stateId 状态编号
     * @return mixed
     */
    public function getOperationsNotBelongState(string $authCode, int $thingId, int $stateId)
    {
        $allOperationIds = $this->operationRepo->getOperationIdsOfThing($thingId);
        $stateOperationIds = $this->operationRepo->getOperationIdsOfState($stateId);

        $noBelongOperationIds = array_diff($allOperationIds, $stateOperationIds);

        $operations = $this->operationRepo->getOperationsByIds($noBelongOperationIds);

        return $operations;
    }

    /**
     * 获取所有的操作类型
     *
     * @param string $authCode 授权码
     * @return array 操作类型集合
     */
    public function getOperationTypes(string $authCode)
    {
        return $this->operationRepo->getOperationTypes();
    }

    /**
     * 获取所有操作形式
     *
     * @param string $authCode 授权码
     * @return array 操作形式集合
     */
    public function getOperationForms(string $authCode)
    {
        return $this->operationRepo->getOperationForms();
    }

    /**
     * 获取字段可操作类型集合
     *
     * @param string $authCode 授权码
     * @return array
     */
    public function getOperationFieldUpdateTypes(string $authCode)
    {
        return $this->operationRepo->getOperationFieldUpdateTypes();
    }

    /**
     * 请求获取用户拥有的所有应用操作权限
     *
     * @param int $appId 应用编号
     * @param int $userId 用户编号
     * @return array
     */
    protected function requestGetAppPermsOfUser(int $appId, int $userId)
    {
        $roleService = RoleService::instance();

        return $roleService->getAppPermsOfAppUser('123', $appId, $userId);
    }
}