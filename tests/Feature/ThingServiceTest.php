<?php

namespace Tests\Feature;

use App\Services\Thing\Service\FieldService;
use App\Services\Thing\Service\FunctionService;
use App\Services\Thing\Service\MessageService;
use App\Services\Thing\Service\OperationService;
use App\Services\Thing\Service\StateService;
use App\Services\Thing\Service\ThingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThingServiceTest extends TestCase
{

    public function testThing()
    {
        $thingService = ThingService::instance();

        // 创建事物
        $thing = $thingService->addThing('123', 1, 'news2', '新闻表2', false);
        $this->assertTrue(is_array($thing));
        $this->assertArrayHasKey('comment', $thing);

        // 获取事物
        $thing = $thingService->getThing('123', $thing['id']);
        $this->assertTrue(is_array($thing));
        $this->assertArrayHasKey('comment', $thing);
        $this->assertEquals('新闻表2', $thing['comment']);

        // 事物列表
        $things = $thingService->getThings('123', 1);
        $this->assertTrue(is_array($things));
        $this->assertArrayHasKey('id', $things[0]);

        // 删除事物
        $result = $thingService->deleteThing('123', $thing['id']);
        $this->assertTrue($result);

        // 创建事物
        $thing = $thingService->addThing('123', 1, 'news1', '新闻表1', true);
        $this->assertTrue(is_array($thing));
        $this->assertArrayHasKey('name', $thing);

        // 更新事物
        $thing = $thingService->updateThing('123', $thing['id'], 'news', '新闻表', null);
        $this->assertTrue(is_array($thing));
        $this->assertEquals('news', $thing['name']);
        $this->assertEquals('新闻表', $thing['comment']);

        return ['thing'=>$thing];
    }

    /**
     * @depends testThing
     */
    public function testField($arr)
    {
        $thingService = ThingService::instance();
        $fieldService = FieldService::instance();

        $thing = $arr['thing'];

        $field = $fieldService->addField('123', $thing['id'], 'title1', '标题1',
            'varchar20', 'input', '', true, true);
        $this->assertTrue(is_array($field));
        $this->assertArrayHasKey('name', $field);

        $field = $fieldService->addField('123', $thing['id'], 'content', '内容1',
            'text', 'text', '', false, false);
        $this->assertTrue(is_array($field));
        $this->assertArrayHasKey('name', $field);

        $fieldState = $fieldService->addField('123', $thing['id'], 'state', '状态(-1:禁用, 1:待审核，4:已发布)',
            'tinyint', 'radio', '[{"label":"禁用", "value":"-1"}]', true, true);
        $this->assertTrue(is_array($fieldState));
        $this->assertArrayHasKey('name', $fieldState);

        $result = $thingService->migrateThing('123', $thing['id']);
        $this->assertTrue($result);

        $fields = $fieldService->getFields('123', $thing['id']);
        $this->assertTrue(is_array($fields));
        $this->assertCount(3, $fields);
        $this->assertEquals('content', $fields[1]['name']);

        $field = $fieldService->updateField('123', $fields[0]['id'], 'title', '标题');
        $this->assertTrue(is_array($field));
        $this->assertEquals('title', $field['name']);

        $field = $fieldService->updateField('123', $fields[1]['id'], null, '内容');
        $this->assertTrue(is_array($field));
        $this->assertEquals('内容', $field['comment']);

        $result = $thingService->migrateThing('123', $thing['id']);
        $this->assertTrue($result);

        $fields = $fieldService->getFields('123', $thing['id']);
        $this->assertTrue(is_array($fields));
        $this->assertCount(3, $fields);
        $this->assertEquals('title', $fields[0]['name']);
        $this->assertEquals('标题', $fields[0]['comment']);
        $this->assertEquals('内容', $fields[1]['comment']);

        $arr['field'] = $field;
        $arr['fieldState'] = $fieldState;

        return $arr;
    }

    /**
     * @depends testField
     */
    public function testState($arr)
    {
        $stateService = StateService::instance();

        $thing = $arr['thing'];
        $fieldState = $arr['fieldState'];

        $stateDisable = $stateService->addState('123', $thing['id'], '禁用', [
            ['fieldId'=>$fieldState['id'], 'symbol'=>'EQ', 'value'=>-1]
        ]);
        $this->assertTrue(is_array($stateDisable));
        $this->assertEquals('禁用', $stateDisable['name']);

        $stateWait = $stateService->addState('123', $thing['id'], '待处理', [
            ['fieldId'=>$fieldState['id'], 'symbol'=>'EQ', 'value'=>1]
        ]);
        $this->assertTrue(is_array($stateWait));
        $this->assertEquals('待处理', $stateWait['name']);

        $stateOther = $stateService->addState('123', $thing['id'], '其他', [
            ['fieldId'=>$fieldState['id'], 'symbol'=>'EQ', 'value'=>10]
        ]);
        $this->assertTrue(is_array($stateOther));
        $this->assertEquals('其他', $stateOther['name']);

        $stateFinish = $stateService->addState('123', $thing['id'], '已发', [
            ['fieldId'=>$fieldState['id'], 'symbol'=>'EQ', 'value'=>3]
        ]);
        $this->assertTrue(is_array($stateFinish));
        $this->assertEquals('已发', $stateFinish['name']);

        // 获取事物状态列表
        $states = $stateService->getStates('123', $thing['id']);
        $this->assertTrue(is_array($states));
        $this->assertCount(4, $states);
        $this->assertArrayHasKey('name', $states[0]);

        // 获取事物状态详情
        $stateFinish = $stateService->getState('123', $stateFinish['id']);
        $this->assertTrue(is_array($stateFinish));
        $this->assertArrayHasKey('conditions', $stateFinish);
        $this->assertCount(1, $stateFinish['conditions']);
        $this->assertArrayHasKey('id', $stateFinish['conditions'][0]);
        $this->assertEquals(3, $stateFinish['conditions'][0]['value']);

        // 修改事物状态
        $stateFinish = $stateService->updateState('123', $stateFinish['id'], '已发布', [
            ['fieldId'=>$fieldState['id'], 'symbol'=>'EQ', 'value'=>4]
        ]);
        $this->assertTrue(is_array($stateFinish));
        $this->assertEquals('已发布', $stateFinish['name']);
        $this->assertTrue(is_array($stateFinish['conditions']));
        $this->assertCount(1, $stateFinish['conditions']);
        $this->assertEquals(4, $stateFinish['conditions'][0]['value']);

        // 删除事物状态
        $result = $stateService->deleteState('123', $stateOther['id']);
        $this->assertTrue($result);

        $states = $stateService->getStates('123', $thing['id']);
        $this->assertTrue(is_array($states));
        $this->assertCount(3, $states);

        $arr['stateDisable'] = $stateDisable;
        $arr['stateWait'] = $stateWait;
        $arr['stateFinish'] = $stateFinish;

        return $arr;
    }

    /**
     * @depends testState
     */
    public function testOperation($arr)
    {
        $operationService = OperationService::instance();

        $thing = $arr['thing'];
        $fieldState = $arr['fieldState'];

        $operationAudit = $operationService->addOperation('123', $thing['id'], '审核', [
            [
                'fieldId' => $fieldState['id'],
                'isShow' => true,
                'operationType' => 'user_input'
            ]
        ]);
        $this->assertTrue(is_array($operationAudit));
        $this->assertArrayHasKey('fields', $operationAudit);

        $operation = $operationService->addOperation('123', $thing['id'], '二审',  [
            [
                'fieldId' => $fieldState['id'],
                'isShow' => true,
                'operationType' => 'user_input'
            ]
        ]);
        $this->assertTrue(is_array($operation));
        $this->assertEquals('二审', $operation['name']);
        $this->assertArrayHasKey('fields', $operation);

        // 修改事物操作
        $operation = $operationService->updateOperation('123', $operation['id'], '二审1', [
            [
                'fieldId' => $fieldState['id'],
                'isShow' => false,
                'operationType' => 'user_input'
            ]
        ]);
        $this->assertTrue(is_array($operation));
        $this->assertEquals('二审1', $operation['name']);
        $this->assertArrayHasKey('fields', $operation);
        $this->assertEquals(false, $operation['fields'][0]['isShow']);

        // 获取事物操作
        $operations = $operationService->getOperations('123', $thing['id']);
        $this->assertTrue(is_array($operations));
        $this->assertCount(2, $operations);

        // 删除事物操作
        $result = $operationService->deleteOperation('123', $operation['id']);
        $this->assertTrue($result);

        $operations = $operationService->getOperations('123', $thing['id']);
        $this->assertTrue(is_array($operations));
        $this->assertCount(1, $operations);

        $arr['operationAudit'] = $operationAudit;

        return $arr;
    }

    /**
     * @depends testOperation
     */
    public function testFlow($arr)
    {
        $flowService = FunctionService::instance();

        $thing = $arr['thing'];
        $operationAudit = $arr['operationAudit'];
        $stateWait = $arr['stateWait'];
        $stateDisable = $arr['stateDisable'];

        // 创建流程
        $flow = $flowService->addFlow('123', $thing['id'], '审核');
        $this->assertTrue(is_array($flow));
        $this->assertArrayHasKey('id', $flow);
        $this->assertEquals('审核', $flow['name']);

        // 更新流程
        $flow = $flowService->updateFlow('123', $flow['id'], '审核流程');
        $this->assertTrue(is_array($flow));
        $this->assertEquals('审核流程', $flow['name']);

        // 获取流程列表
        $flows = $flowService->getFlows('123', $thing['id']);
        $this->assertTrue(is_array($flows));
        $this->assertCount(1, $flows);

        // 创建流程节点
        $flowNodeAudit = $flowService->addFlowNode('123', $flow['id'], $stateWait['id'], 'role',
            1, [$operationAudit['id']]);
        $this->assertTrue(is_array($flowNodeAudit));
        $this->assertArrayHasKey('operationIds', $flowNodeAudit);
        $this->assertArrayHasKey('operatorValue', $flowNodeAudit);
        $this->assertEquals(1, $flowNodeAudit['operatorValue']);

        $flowNode = $flowService->addFlowNode('123', $flow['id'], $stateDisable['id'], 'role',
            1, [$operationAudit['id']]);
        $this->assertTrue(is_array($flowNode));
        $this->assertArrayHasKey('operationIds', $flowNode);
        $this->assertArrayHasKey('operatorValue', $flowNode);
        $this->assertEquals(1, $flowNode['operatorValue']);

        // 获取流程节点列表
        $flowNodes = $flowService->getFlowNodes('123', $flow['id']);
        $this->assertTrue(is_array($flowNodes));
        $this->assertCount(2, $flowNodes);

        // 更新流程节点
        $flowNode = $flowService->updateFlowNode('123', $flowNode['id'], $stateDisable['id'],
            'table_field', 'title', null);
        $this->assertTrue(is_array($flowNode));
        $this->assertArrayHasKey('operatorType', $flowNode);
        $this->assertEquals('table_field', $flowNode['operatorType']);

        // 获取指定流程信息
        $flow = $flowService->getFlow('123', $flow['id']);
        $this->assertTrue(is_array($flow));
        $this->assertArrayHasKey('nodes', $flow);
        $this->assertTrue(is_array($flow['nodes']));
        $this->assertCount(2, $flow['nodes']);

        // 删除流程节点
        $result = $flowService->deleteFlowNode('123', $flowNodeAudit['id']);
        $this->assertTrue($result);

        // 获取流程节点列表
        $flowNodes = $flowService->getFlowNodes('123', $flow['id']);
        $this->assertTrue(is_array($flowNodes));
        $this->assertCount(1, $flowNodes);

        // 删除流程
        $result = $flowService->deleteFlow('123', $flow['id']);
        $this->assertTrue($result);


        // 获取流程列表
        $flows = $flowService->getFlows('123', $thing['id']);
        $this->assertTrue(is_array($flows));
        $this->assertCount(0, $flows);

        return $arr;
    }

    /**
     * @depends testFlow
     */
    public function testMessage($arr)
    {
        $messageService = MessageService::instance();

        $thing = $arr['thing'];
        $stateWait = $arr['stateWait'];
        $stateDisable = $arr['stateDisable'];

        // 创建事物消息定义
        $message = $messageService->addDefinition('123', $stateWait['id'], 'role', 1,
            '待审核消息！');
        $this->assertTrue(is_array($message));
        $this->assertArrayHasKey('receiverType', $message);

        // 获取事物消息定义
        $message = $messageService->getDefinition('123', $message['id']);
        $this->assertTrue(is_array($message));
        $this->assertArrayHasKey('receiverValue', $message);
        $this->assertEquals(1, $message['receiverValue']);

        // 更新事物消息定义
        $message = $messageService->updateDefinition('123', $message['id'], $stateDisable['id'],
            'role', 1, '已禁用消息！');
        $this->assertTrue(is_array($message));
        $this->assertArrayHasKey('stateId', $message);
        $this->assertEquals($stateDisable['id'], $message['stateId']);

        // 获取事物消息定义列表
        $messages = $messageService->getDefinitions('123', $thing['id']);
        $this->assertTrue(is_array($messages));
        $this->assertCount(1, $messages);
        $this->assertEquals('已禁用消息！', $messages[0]['content']);

        // 删除事物消息定义
        $result = $messageService->deleteDefinition('123', $message['id']);
        $this->assertTrue($result);

        return $arr;
    }
}
