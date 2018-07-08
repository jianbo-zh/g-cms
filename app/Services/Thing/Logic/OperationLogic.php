<?php

namespace App\Services\Thing\Logic;

use App\Services\_Base\Exception;
use App\Services\_Base\Logic;
use App\Services\Thing\Repository\OperationRepository;
use App\Services\Thing\Repository\ThingRepository;
use Illuminate\Support\Facades\DB;

class OperationLogic extends Logic
{
    /**
     * @var OperationRepository 
     */
    protected $operationRepo;

    /**
     * @var ThingRepository
     */
    protected $thingRepo;

    /**
     * OperationLogic constructor.
     */
    protected function __construct()
    {
        $this->operationRepo = OperationRepository::instance();
        $this->thingRepo = ThingRepository::instance();
    }

    /**
     * 给事物添加一个操作
     *
     * @param int $thingId 事物编号
     * @param string $name 操作名称
     * @param string $operationType 操作类型
     * @param string $operationForm 操作形式
     * @param array $fields 操作字段[['fieldId'=>12, 'isShow'=>true, 'operationType'=>'current_user'],...]
     * @return array 新增操作信息
     * @throws \Exception
     */
    public function addOperation(int $thingId, string $name, string $operationType, string $operationForm, array $fields)
    {
        try{
            DB::beginTransaction();

            $thing = $this->thingRepo->getThing($thingId);
            if(! $thing){
                throw new Exception('我找到对应的事物！');
            }

            $operation = $this->operationRepo->addOperation($thingId, $name, $operationType, $operationForm);
            if(empty($operation)){
                throw new Exception('添加状态失败！');
            }
            $operation['fields'] = [];

            foreach ($fields as $val){
                if(empty($val['fieldId']) || !is_numeric($val['fieldId'])){
                    throw new Exception('操作字段参数不能为空或错误！');
                }
                if(!isset($val['isShow'])){
                    throw new Exception('是否展示不能为空！');
                }
                if(!isset($val['updateType'])){
                    throw new Exception('更新类型必须设置！');
                }

                $field = $this->operationRepo->addOperationField($operation['id'], $val['fieldId'],
                    $val['isShow'], $val['updateType']);

                if(empty($field)){
                    throw new Exception('添加操作字段失败！');
                }
                $operation['fields'][] = $field;
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $operation;
    }

    /**
     * 更新事物操作信息
     *
     * @param int $operationId 操作编号
     * @param string $name 最新操作名
     * @param string $operationType 操作类型
     * @param string $operationForm 操作形式
     * @param array $fields 最新操作字段
     * @return array 成功返回更新后的操作信息
     * @throws \Exception
     */
    public function updateOperation(int $operationId, ?string $name=null, ?string $operationType=null,
                                    ?string $operationForm=null, ?array $fields=null)
    {
        try{
            DB::beginTransaction();

            $operation = $this->operationRepo->getOperation($operationId);
            if(! $operation){
                throw new Exception('未找到对应的状态！');
            }

            if(!is_null($name) || !is_null($operationType) || !is_null($operationForm)){
                $operation = $this->operationRepo->updateOperation($operationId, $name, $operationType, $operationForm);
                if(! $operation){
                    throw new Exception('更新操作失败！');
                }
            }

            $operation['fields'] = [];

            if(!is_null($fields)){

                $oldIds = [];
                $result = $this->operationRepo->getOperationFields($operationId, ['id']);
                foreach ($result as $val){
                    $oldIds[] = $val['id'];
                }

                foreach ($fields as $val){
                    if(empty($val['fieldId']) || !is_numeric($val['fieldId'])){
                        throw new Exception('操作字段参数不能为空或错误！');
                    }
                    if(!isset($val['isShow'])){
                        throw new Exception('是否展示不能为空！');
                    }
                    if(!isset($val['updateType'])){
                        throw new Exception('操作值定义必须设置！');
                    }

                    if(!empty($val['id'])){
                        $field = $this->operationRepo->updateOperationField($val['id'], $val['isShow'],
                            $val['updateType']);
                        if(! $field){
                            throw new Exception('更新操作字段失败！');
                        }
                        $oldIds = array_diff($oldIds, [$val['id']]);

                    }else{
                        $field = $this->operationRepo->addOperationField($operationId, $val['fieldId'],
                            $val['isShow'], $val['updateType']);
                        if(! $field){
                            throw new Exception('添加操作字段失败！');
                        }
                    }

                    $operation['fields'][] = $field;
                }

                if(!empty($oldIds)){
                    foreach ($oldIds as $val){
                        $result = $this->operationRepo->deleteOperationField($val);
                        if($result === false){
                            throw new \Exception('删除操作字段失败！');
                        }
                    }
                }
            }else{
                $operation['fields'] = $this->operationRepo->getOperationFields($operationId);
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $operation;
    }

    /**
     * 删除操作及其字段定义
     *
     * @param int $operationId 操作编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteOperation(int $operationId)
    {
        try{
            DB::beginTransaction();

            $operation = $this->operationRepo->getOperation($operationId);

            if(!$operation){
                throw new Exception('未找到对应的操作！');
            }

            $fields = $this->operationRepo->getOperationFields($operationId);

            foreach ($fields as $val){
                $result = $this->operationRepo->deleteOperationField($val['id']);
                if($result === false){
                    throw new Exception('删除操作字段失败！');
                }
            }

            $result = $this->operationRepo->deleteOperation($operationId);
            if($result === false){
                throw new Exception('删除操作失败！');
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return true;
    }

}