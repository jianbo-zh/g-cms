<?php

namespace App\Services\Thing\Logic;

use App\Services\_Base\Exception;
use App\Services\_Base\Logic;
use App\Services\Thing\Repository\StateRepository;
use App\Services\Thing\Repository\ThingRepository;
use Illuminate\Support\Facades\DB;

class StateLogic extends Logic
{
    /**
     * @var StateRepository
     */
    protected $stateRepo;

    /**
     * @var ThingRepository
     */
    protected $thingRepo;

    /**
     * StateLogic constructor.
     */
    protected function __construct()
    {
        $this->stateRepo = StateRepository::instance();
        $this->thingRepo = ThingRepository::instance();
    }

    /**
     * 给事物添加一个状态
     *
     * @param int $thingId 事物编号
     * @param string $name 状态名称
     * @param array $conditions 状态条件[['fieldId'=>12, 'symbol'=>'EQ', 'value'=>1],...]
     * @return array 新增状态信息
     * @throws \Exception
     */
    public function addState(int $thingId, string $name, array $conditions)
    {
        try{
            DB::beginTransaction();

            $thing = $this->thingRepo->getThing($thingId);
            if(! $thing){
                throw new Exception('我找到对应的事物！');
            }

            $state = $this->stateRepo->addState($thingId, $name);
            if(empty($state)){
                throw new Exception('添加状态失败！');
            }
            $state['conditions'] = [];

            foreach ($conditions as $val){
                if(empty($val['fieldId']) || !is_numeric($val['fieldId'])){
                    throw new Exception('条件字段参数不能为空或错误！');
                }
                if(empty($val['symbol'])){
                    throw new Exception('操作符参数不能为空！');
                }
                if(!array_key_exists('value', $val)){
                    throw new Exception('操作值参数必须设置！');
                }

                $cond = $this->stateRepo->addStateCondition($state['id'], $val['fieldId'], $val['symbol'],
                    $val['value']);
                if(empty($cond)){
                    throw new Exception('添加状态条件失败！');
                }
                $state['conditions'][] = $cond;
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $state;
    }

    /**
     * 更新事物状态信息
     *
     * @param int $stateId 状态编号
     * @param string $name 最新状态名
     * @param array $conditions 最新状态条件
     * @return array 成功返回更新后的状态信息
     * @throws \Exception
     */
    public function updateState(int $stateId, ?string $name=null, ?array $conditions=null)
    {
        try{
            DB::beginTransaction();

            $state = $this->stateRepo->getState($stateId);
            if(! $state){
                throw new Exception('我找到对应的状态！');
            }

            if(!is_null($name) && $state['name'] != $name){
                $state = $this->stateRepo->updateState($stateId, $name);
                if(! $state){
                    throw new Exception('更新状态名称失败！');
                }
            }

            $state['conditions'] = [];

            if(!is_null($conditions)){

                $oldCondIds = [];
                $result = $this->stateRepo->getStateConditions($stateId, ['id']);
                foreach ($result as $val){
                    $oldCondIds[] = $val['id'];
                }

                foreach ($conditions as $val){
                    if(empty($val['fieldId']) || !is_numeric($val['fieldId'])){
                        throw new Exception('条件字段参数不能为空或错误！');
                    }
                    if(empty($val['symbol'])){
                        throw new Exception('操作符参数不能为空！');
                    }
                    if(! array_key_exists('value', $val)){
                        throw new Exception('操作值参数必须设置！');
                    }

                    if(!empty($val['id'])){
                        $cond = $this->stateRepo->updateStateCondition($val['id'], $stateId, $val['fieldId'],
                            $val['symbol'], $val['value']);
                        if(! $cond){
                            throw new Exception('更新状态条件失败！');
                        }
                        $oldCondIds = array_diff($oldCondIds, [$val['id']]);

                    }else{
                        $cond = $this->stateRepo->addStateCondition($stateId, $val['fieldId'], $val['symbol'],
                            $val['value']);
                        if(! $cond){
                            throw new Exception('添加状态条件失败！');
                        }
                    }

                    $state['conditions'][] = $cond;
                }

                if(!empty($oldCondIds)){
                    foreach ($oldCondIds as $val){
                        $result = $this->stateRepo->deleteStateCondition($val);
                        if($result === false){
                            throw new \Exception('删除状态条件失败！');
                        }
                    }
                }
            }else{
                $state['conditions'] = $this->stateRepo->getStateConditions($stateId);
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $state;
    }

    /**
     * 删除状态及其条件
     *
     * @param int $stateId 状态编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteState(int $stateId)
    {
        try{
            DB::beginTransaction();

            $state = $this->stateRepo->getState($stateId);

            if(!$state){
                throw new Exception('未找到对应的状态！');
            }

            $conditions = $this->stateRepo->getStateConditions($stateId);

            foreach ($conditions as $val){
                $result = $this->stateRepo->deleteStateCondition($val['id']);
                if($result === false){
                    throw new Exception('删除状态条件失败！');
                }
            }
            $result = $this->stateRepo->deleteState($stateId);
            if($result === false){
                throw new Exception('删除状态失败！');
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return true;
    }

}