<?php

namespace App\Services\Thing\Service;

use App\Services\_Base\Exception;
use App\Services\_Base\Service;
use App\Services\Thing\Logic\ThingLogic;
use App\Services\Thing\Repository\FieldRepository;
use App\Services\Thing\Repository\StateRepository;
use App\Services\Thing\Repository\ThingRepository;

class ThingService extends Service
{
    /**
     * @var ThingRepository
     */
    protected $thingRepo;

    /**
     * @var StateRepository
     */
    protected $stateRepo;

    /**
     * @var FieldRepository
     */
    protected $fieldRepo;

    /**
     * @var ThingLogic
     */
    protected $thingLogic;

    protected function __construct()
    {
        $this->thingRepo = ThingRepository::instance();
        $this->thingLogic = ThingLogic::instance();
        $this->stateRepo = StateRepository::instance();
        $this->fieldRepo = FieldRepository::instance();
    }

    /**
     * 向指定的应用添加一个事物
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param string $name 名称
     * @param string $dec 描述
     * @return array 新增的事物信息
     * @throws \Exception
     */
    public function addThing(string $authCode, int $appId, string $name, string $dec)
    {

        return $this->thingLogic->addThing($appId, $name, $dec);
    }

    /**
     * 获取事物详情
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return array 事物详情
     */
    public function getThing(string $authCode, int $thingId)
    {
        return $this->thingRepo->getThing($thingId);
    }

    /**
     * 获取事物内容列表
     *
     * @param string $authCode 授权码
     * @param int $thingId 事务编号
     * @param array $queries 查询条件[['name'=>'username', 'value'=>'中']]
     * @param int $offset 返回偏移量
     * @param int|null $limit 返回数量
     * @return array 内容列表
     * @throws \App\Services\_Base\Exception
     */
    public function getThingContents(string $authCode, int $thingId, array $queries=[], int $offset=0, int $limit=0)
    {
        return $this->commonGetThingContents($thingId, $queries, $offset, $limit);
    }

    /**
     * 获取事物内容带状态信息
     *
     * @param string $authCode 授权码
     * @param int $thingId 事务编号
     * @param array $queries 查询条件[['name'=>'username', 'value'=>'中']]
     * @param int $offset 返回偏移量
     * @param int|null $limit 返回数量
     * @return array 内容列表
     * @throws Exception
     */
    public function getThingContentsWithState(string $authCode, int $thingId, array $queries=[], int $offset=0,
                                              int $limit=0)
    {
        $thingContent = $this->commonGetThingContents($thingId, $queries, $offset, $limit);

        if(!empty($thingContent['data'])){
            $contents = $thingContent['data'];

            $stateConditions = $this->stateRepo->getStateConditionsOfThing($thingId);
            if(!empty($stateConditions)){
                $fieldIds = [];
                foreach ($stateConditions as $value){
                    if(! in_array($value['fieldId'], $fieldIds)){
                        $fieldIds[] = $value['fieldId'];
                    }
                }

                $fields = $this->fieldRepo->getFieldsByIds($fieldIds, ['id', 'name']);
                $fieldNameMap = [];
                foreach ($fields as $field){
                    $fieldNameMap[$field['id']] = camel_case($field['name']);
                }

                $state2Conditions = [];
                foreach ($stateConditions as $stateCondition){
                    if(!isset($state2Conditions[$stateCondition['stateId']])){
                        $state2Conditions[$stateCondition['stateId']] = [];
                    }
                    $state2Conditions[$stateCondition['stateId']][] = $stateCondition;
                }

                foreach ($contents as $key => $content){
                    $currentStateIds = [];
                    foreach ($state2Conditions as $stateId => $conditions){
                        $isOk = true;
                        foreach ($conditions as $condition) {
                            $name = $fieldNameMap[$condition['fieldId']];
                            if (!array_key_exists($name, $content)) {
                                throw new Exception("未找到对应的{$name}字段！");
                            }
                            $value = $content[$name];
                            switch ($condition['symbol']) {
                                case 'EQ':
                                    if ((string)$value !== (string)$condition['value']) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'NEQ':
                                    if ((string)$value === (string)$condition['value']) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'GT':
                                    if ((string)$value <= (string)$condition['value']) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'LT':
                                    if ((string)$value >= (string)$condition['value']) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'EGT':
                                    if ((string)$value < (string)$condition['value']) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'ELT':
                                    if ((string)$value > (string)$condition['value']) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'NULL':
                                    if (! is_null($value)) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'NOT NULL':
                                    if (is_null($value)) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'BETWEEN':
                                    $between = explode(',', $condition['value']);
                                    if (count($between) != 2) {
                                        throw new Exception("字段{$name}配置错误！");
                                    }
                                    $min = (int)trim($between[0]);
                                    $max = (int)trim($between[1]);
                                    if ($value < $min || $value > $max) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'IN':
                                    $ins = explode(',', $condition['value']);
                                    if (count($ins) == 0) {
                                        throw new Exception("字段{$name}配置错误！");
                                    }
                                    $ins = array_map('trim', $ins);
                                    if (! in_array($value, $ins)) {
                                        $isOk = false;
                                    }
                                    break;
                                case 'FIELD':
                                    $otherKey = trim($condition['value']);
                                    if ($value != $content[$otherKey]) {
                                        $isOk = false;
                                    }
                                    break;
                            }

                            if(! $isOk){
                                break;
                            }
                        }

                        if($isOk){
                            $currentStateIds[] = $stateId;
                        }
                    }
                    $contents[$key]['_stateIds'] = $currentStateIds;
                }

            }else{
                foreach ($contents as $key => $content){
                    $contents[$key]['_stateIds'] = [];
                }
            }

            $thingContent['data'] = $contents;
        }

        return $thingContent;
    }

    /**
     * 获取事物内容
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param int $contentId 内容编号
     * @return array 事物内容
     * @throws Exception
     */
    public function getThingContent(string $authCode, int $thingId, int $contentId)
    {
        return $this->thingRepo->getThingContent($thingId, $contentId);
    }

    /**
     * 获取事物内容列表
     *
     * @param int $thingId 事务编号
     * @param array $queries 查询条件[['name'=>'username', 'value'=>'中']]
     * @param int $offset 返回偏移量
     * @param int|null $limit 返回数量
     * @return array 内容列表
     * @throws Exception
     */
    protected function commonGetThingContents(int $thingId, array $queries=[], int $offset=0, int $limit=0)
    {
        $validConditions = [];
        $queryFields = $this->thingRepo->getThingContentQueryFields($thingId);
        foreach ($queries as $query){
            if(!isset($query['name']) || !isset($query['value'])){
                continue;
            }
            foreach ($queryFields as $queryField){
                if($query['name'] === $queryField['name']){
                    $validConditions[] = [
                        'name' => $query['name'],
                        'value' => $query['value'],
                        'showType' => $queryField['showType']
                    ];
                }
            }
        }

        return $this->thingRepo->getThingContents($thingId, $validConditions, $offset, $limit);
    }

    /**
     * 新增事物内容
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param int $operationId 操作编号
     * @param array $inputData 用户输入数据
     * @param array $contextData 当前环境信息 []
     * @return array|false 成功返回事物内容，失败返回false
     * @throws \Exception
     */
    public function addThingContent(string $authCode, int $thingId, int $operationId, array $inputData,
                                    array $contextData)
    {
        $contextData['current_time'] = !empty($contextData['current_time']) ? $contextData['current_time'] :
            date('Y-m-d H:i:s');

        return $this->thingLogic->addThingContent($thingId, $operationId, $inputData, $contextData);
    }

    /**
     *
     * 更新事物内容
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param int $operationId 操作编号
     * @param int $contentId 内容编号
     * @param array $inputData 用户输入数据
     * @param array $contextData 环境数据
     * @return array|bool 是否成功，成功则返回更新后的事物内容，失败返回false
     * @throws \Exception
     */
    public function updateThingContent(string $authCode, int $thingId, int $operationId, int $contentId,
                                       array $inputData, array $contextData)
    {
        return $this->thingLogic->updateThingContent($thingId, $operationId, $contentId, $inputData, $contextData);
    }

    /**
     * 删除事物内容
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param int $contentId 事物内容
     * @return bool 是否成功
     * @throws \App\Services\_Base\Exception
     * @throws \Exception
     */
    public function deleteThingContent(string $authCode, int $thingId, int $contentId)
    {
        return $this->thingRepo->deleteThingContent($thingId, $contentId);
    }

    /**
     * 获取事物查询条件
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return mixed
     */
    public function getThingContentQueryFields(string $authCode, int $thingId)
    {
        $fields = $this->thingRepo->getThingContentQueryFields($thingId);

        return $fields;
    }

    /**
     * 获取应用的事物列表
     *
     * @param string $authCode 授权码
     * @param int $appId 应用编号
     * @param array $condition 查询条件 [name]
     * @param int $offset 偏移数量
     * @param int $limit 返回数量
     * @return array 事物列表
     */
    public function getThings(string $authCode, int $appId, array $condition=[], int $offset=0, int $limit=20)
    {
        return $this->thingRepo->getThings($appId, $condition, ['id', 'app_id', 'name', 'description', 'created_at',
            'updated_at'], ['id', 'asc'], $offset, $limit);
    }

    /**
     * 更新事物信息
     * 指定字段为null则为不更新该字段
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @param string|null $name 名称
     * @param string|null $dec 描述
     * @return array|bool 成功则返回更新后的事物，失败返回false
     * @throws Exception
     */
    public function updateThing(string $authCode, int $thingId, ?string $name=null, ?string $dec=null)
    {
        $result = $this->thingRepo->updateThing($thingId, $name, $dec);

        return $result;
    }

    /**
     * 删除指定事物
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function deleteThing(string $authCode, int $thingId)
    {

        $result = $this->thingLogic->deleteThing($thingId);

        return ($result !== false) ? true : false;
    }

    /**
     * 执行事物字段迁移工作
     *
     * @param string $authCode 授权码
     * @param int $thingId 事物编号
     * @return bool 是否成功
     * @throws \Exception
     */
    public function migrateThing(string $authCode, int $thingId)
    {

        $result = $this->thingLogic->migrateThing($thingId);

        return $result;
    }
}