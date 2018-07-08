<?php

namespace App\Services\Thing\Service;

use App\Services\_Base\Exception;
use App\Services\_Base\Service;
use App\Services\Thing\Logic\StateLogic;
use App\Services\Thing\Repository\StateRepository;

class StateService extends Service
{
    /**
     * @var StateRepository
     */
    protected $stateRepo;

    /**
     * @var StateLogic
     */
    protected $stateLogic;

    /**
     * StateService constructor.
     */
    protected function __construct()
    {
        $this->stateRepo = StateRepository::instance();
        $this->stateLogic = StateLogic::instance();
    }

    /**
     * 获取事物的所有状态
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return array 状态列表
     */
    public function getStates(string $authCode, int $thingId)
    {

        return $this->stateRepo->getStates($thingId);
    }

    /**
     * 获取状态详情
     *
     * @param string $authCode 授权码
     * @param int $stateId 状态编号
     * @return array 状态信息
     */
    public function getState(string $authCode, int $stateId)
    {
        $state = $this->stateRepo->getState($stateId);

        $state['conditions'] = $this->stateRepo->getStateConditions($stateId);

        return $state;
    }

    /**
     * 给事物添加状态
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param string $name 状态名
     * @param array $conditions 状态条件 [['fieldId'=>12, 'symbol'=>'EQ', 'value'=>1],...]
     * @return array 新增状态信息
     * @throws \Exception
     */
    public function addState(string $authCode, int $thingId, string $name, array $conditions)
    {
        return $this->stateLogic->addState($thingId, $name, $conditions);
    }

    /**
     * 更新状态信息
     * 参数为null时表示不更新该对应信息
     *
     * @param string $authCode 授权码
     * @param int $stateId 状态编号
     * @param string|null $name 状态名
     * @param array|null $conditions 状态条件
     * @return array 更新后台的状态信息
     * @throws Exception
     * @throws \Exception
     */
    public function updateState(string $authCode, int $stateId, ?string $name=null, ?array $conditions=null)
    {

        if(is_null($name) && is_null($conditions)){
            throw new Exception('没有更新的信息！');
        }

        $state = $this->stateLogic->updateState($stateId, $name, $conditions);

        return $state;
    }

    /**
     * 删除指定状态
     *
     * @param string $authCode 授权码
     * @param int $stateId 状态编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteState(string $authCode, int $stateId)
    {

        $result = $this->stateLogic->deleteState($stateId);

        return ($result !== false) ? true : false;
    }

    /**
     * 获取状态条件所有操作符号
     *
     * @param string $authCode 授权码
     * @return array
     */
    public function getStateConditionSymbols(string $authCode)
    {
        return $this->stateRepo->getStateConditionSymbols();
    }

    /**
     * 绑定状态和操作
     *
     * @param string $authCode 授权码
     * @param int $stateId 状态编号
     * @param int $operationId 操作编号
     * @return array
     */
    public function addStateOperation(string $authCode, int $stateId, int $operationId)
    {
        return $this->stateRepo->bindStateAndOperation($stateId, $operationId);
    }

    /**
     * 解绑状态和操作
     *
     * @param string $authCode 授权码
     * @param int $stateId 状态编号
     * @param int $operationId 操作编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteStateOperation(string $authCode, int $stateId, int $operationId)
    {
        $result = $this->stateRepo->unbindStateAndOperation($stateId, $operationId);

        return $result !== false ? true : false;
    }

    /**
     * 获取事物的状态和操作关联集合
     *
     * @param int $thingId 事物编号
     * @return array|mixed
     */
    public function getStateAndOperationRelationOfThing(int $thingId)
    {
        $relations = [];

        $states = $this->stateRepo->getStates($thingId, ['id']);
        if(! empty($states)){
            $stateIds = [];
            foreach ($states as $state){
                $stateIds[] = $state['id'];
            }

            $relations = $this->stateRepo->getState2OperationByStateIds($stateIds);
        }

        return $relations;
    }
}