<?php

namespace App\Services\Thing\Service;

use App\Services\_Base\Service;
use App\Services\Thing\Repository\MessageDefinitionRepository;

/**
 * 消息定义服务类
 * 为事物在不同状态时，定义一些消息通知内容
 *
 * Class MessageService
 * @package App\Services\Thing\Service
 */
class MessageService extends Service
{
    /**
     * @var MessageDefinitionRepository
     */
    protected $definitionRepo;

    /**
     * MessageService constructor.
     */
    protected function __construct()
    {
        $this->definitionRepo = MessageDefinitionRepository::instance();
    }

    /**
     * 获取消息定义列表
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return array 消息定义列表
     */
    public function getDefinitions(string $authCode, int $thingId)
    {
        return $this->definitionRepo->getDefinitions($thingId);
    }

    /**
     * 获取消息定义详情
     *
     * @param string $authCode 授权码
     * @param int $definitionId 消息定义编号
     * @return array 消息定义
     */
    public function getDefinition(string $authCode, int $definitionId)
    {
        return $this->definitionRepo->getDefinition($definitionId);
    }

    /**
     * 给事物添加一个消息定义
     *
     * @param string $authCode 授权码
     * @param int $stateId 状态编号
     * @param string $receiverType 接受者类型
     * @param string $receiverValue 接受者值
     * @param string $content 消息定义内容
     * @return mixed
     */
    public function addDefinition(string $authCode, int $stateId, string $receiverType, string $receiverValue,
                                  string $content)
    {
        return $this->definitionRepo->addDefinition($stateId, $receiverType, $receiverValue, $content);
    }


    /**
     * 更新指定消息定义
     *
     * @param string $authCode 授权码
     * @param int $definitionId 消息定义编号
     * @param int|null $stateId 事物状态编号
     * @param string|null $receiverType 接受者类型
     * @param string|null $receiverValue 接受者值
     * @param string|null $content 消息定义内容
     * @return bool|mixed 成功则返回更新的消息定义，失败返回false
     * @throws \App\Services\_Base\Exception
     */
    public function updateDefinition(string $authCode, int $definitionId, ?int $stateId=null,
                                     ?string $receiverType=null, ?string $receiverValue=null, ?string $content=null)
    {
        return $this->definitionRepo->updateDefinition($definitionId, $stateId, $receiverType, $receiverValue,
            $content);
    }

    /**
     * 删除消息定义
     *
     * @param string $authCode 授权码
     * @param int $definitionId 消息定义编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteDefinition(string $authCode, int $definitionId)
    {
        $result = $this->definitionRepo->deleteDefinition($definitionId);

        return ($result !== false) ? true : false;
    }

}