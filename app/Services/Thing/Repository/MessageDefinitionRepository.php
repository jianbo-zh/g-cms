<?php

namespace App\Services\Thing\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\Thing\Model\MessageDefinitionModel;
use App\Services\Thing\Model\StateModel;

class MessageDefinitionRepository extends Repository
{
    /**
     * 获取事物的所有消息定义
     *
     * @param int $thingId 事物编号
     * @return array 消息定义列表
     */
    public function getDefinitions(int $thingId)
    {
        $states = StateModel::where('thing_id', $thingId)->select('id')->get();

        if(! $states){
            return [];
        }
        $stateIds = [];
        foreach ($states as $val){
            $stateIds[] = $val->id;
        }

        $definitions = MessageDefinitionModel::whereIn('state_id', $stateIds)->get();

        return $this->normalizeReturn($definitions);
    }

    /**
     * 获取消息定义详情
     *
     * @param int $definitionId 消息定义编号
     * @return mixed
     */
    public function getDefinition(int $definitionId)
    {
        $definition = MessageDefinitionModel::find($definitionId);

        return $this->normalizeReturn($definition);
    }

    /**
     * 添加消息定义
     *
     * @param int $stateId 事物状态编号
     * @param string $receiverType 接收者类型
     * @param string $receiverValue 接收者值
     * @param string $content 消息内容
     * @return mixed
     */
    public function addDefinition(int $stateId, string $receiverType, string $receiverValue, string $content)
    {
        $definition = MessageDefinitionModel::create([
            'state_id'          => $stateId,
            'receiver_type'     => $receiverType,
            'receiver_value'    => $receiverValue,
            'content'           => $content
        ]);

        return $this->normalizeReturn($definition);
    }

    /**
     * 更新消息定义
     *
     * @param int $definitionId 消息定义编号
     * @param int|null $stateId 事物状态编号
     * @param string|null $receiverType 接收者类型 ['role', 'table_field']
     * @param string|null $receiverValue 接收者值
     * @param string|null $content 消息定义内容
     * @return bool|mixed 是否成功，成功则返回更新后的消息，失败返回false
     * @throws Exception
     */
    public function updateDefinition(int $definitionId, int $stateId=null, string $receiverType=null,
                                     string $receiverValue=null, string $content=null)
    {
        if(is_null($stateId) && is_null($receiverType) && is_null($receiverValue) && is_null($content)){
            throw new Exception('更新数据不能都为空！');
        }

        $definition = MessageDefinitionModel::findOrFail($definitionId);

        if(!is_null($stateId)){
            $definition->state_id = $stateId;
        }
        if(!is_null($receiverType)){
            if(!in_array($receiverType, array_keys(MessageDefinitionModel::getReceiverTypes()))){
                throw new Exception('错误的接收类型！');
            }
            $definition->receiver_type = $receiverType;
        }
        if(!is_null($receiverValue)){
            $definition->receiver_value = $receiverValue;
        }
        if(!is_null($content)){
            $definition->content = $content;
        }

        $result = $definition->save();

        return $result ? $this->normalizeReturn($definition) : false;
    }

    /**
     * 删除消息定义
     *
     * @param int $definitionId 消息定义编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteDefinition(int $definitionId)
    {
        $definition = MessageDefinitionModel::findOrFail($definitionId);

        return $definition->delete();
    }
}