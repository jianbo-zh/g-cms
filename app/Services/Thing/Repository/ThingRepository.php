<?php

namespace App\Services\Thing\Repository;

use App\Services\_Base\Exception;
use App\Services\_Base\Repository;
use App\Services\Thing\Model\FieldModel;
use App\Services\Thing\Model\ThingModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ThingRepository extends Repository
{
    /**
     * 为应用添加事物
     *
     * @param int $appId 应用编号
     * @param string $name 名称
     * @param string $dec 描述
     * @return array 事物信息
     */
    public function addThing(int $appId, string $name, string $dec)
    {
        $thing = ThingModel::create([
            'app_id'        => $appId,
            'name'          => $name,
            'description'   => $dec,
            'table_name'    => '',      // 表结构迁移时自动生成
        ]);

        return $this->normalizeReturn($thing);
    }

    /**
     * 更新事物信息
     *
     * @param int $thingId 事物编号
     * @param string|null $name 名称
     * @param string|null $dec 描述
     * @return bool|array 成功则返回更新后的事物信息，失败返回false
     * @throws Exception
     */
    public function updateThing(int $thingId, ?string $name=null, ?string $dec=null)
    {
        if(is_null($name) || is_null($dec)){
            throw new Exception('更新事物不能都为空！');
        }
        $thing = ThingModel::findOrFail($thingId);
        if(!is_null($name)){
            $thing->name = $name;
        }
        if(!is_null($dec)){
            $thing->description = $dec;
        }

        $result = $thing->save();

        return $result ? $this->normalizeReturn($thing) : false;
    }

    /**
     * 更新事物的表名
     *
     * @param int $thingId 事物编号
     * @param string $tableName 表名称
     * @return bool|mixed 是否成功，成功则返回更新后的事物，失败返回false
     */
    public function updateThingTableName(int $thingId, string $tableName)
    {
        $thing = ThingModel::findOrFail($thingId);
        $thing->table_name = $tableName;

        $result = $thing->save();

        return $result ? $this->normalizeReturn($thing) : false;
    }

    /**
     * 获取事物详情
     *
     * @param int $thingId 事物编号
     * @return array 事物信息
     */
    public function getThing(int $thingId)
    {
        $thing = ThingModel::find($thingId);

        return $this->normalizeReturn($thing);
    }

    /**
     * 获取应用拥有的事物列表
     *
     * @param int $appId 应用编号
     * @param array $condition 查询条件
     * @param array 返回字段 ['id', 'app_id', 'name', 'description', 'created_at', 'updated_at']
     * @param array $orderBy 排序依据：["id", "asc|desc"]
     * @param int $offset 偏移数量
     * @param int|null $limit 返回数量
     * @return array 事物列表数组
     */
    public function getThings(int $appId, ?array $condition=null, ?array $fields=null, ?array $orderBy=null,
                              int $offset=0, ?int $limit=null)
    {
        $things = ThingModel::where('app_id', $appId)
            ->when($condition, function (Builder $query) use ($condition){
                if(!empty($condition['name'])){
                    $query->where('name', $condition['name']);
                }
                return $query;
            })
            ->when($fields, function (Builder $query) use ($fields){
                return $query->select($fields);
            })
            ->when($orderBy, function (Builder $query) use ($orderBy){
                return $query->orderBy(...$orderBy);
            })
            ->when($limit > 0, function (Builder $query) use ($offset, $limit){
                return $query->offset($offset)->limit($limit);
            })
            ->get();

        return $this->normalizeReturn($things);
    }

    /**
     * 删除事物
     *
     * @param int $thingId 事物编号
     * @return bool|null 是否成功
     * @throws \Exception
     */
    public function deleteThing(int $thingId)
    {
        $thing = ThingModel::findOrFail($thingId);

        return $thing->delete();
    }

    /**
     * 更新事物字段设置状态为已迁移
     *
     * @param int $thingId 事物编号
     * @return bool 是否成功
     * @throws Exception
     */
    public function setThingMigrated(int $thingId)
    {
        $fields = ThingModel::find($thingId)->fields()
            ->where('state', '<>', FieldModel::STATE_MIGRATED)->get();

        foreach ($fields as $field){
            $field->name_old = '';
            $field->state = FieldModel::STATE_MIGRATED;

            if(! $field->save()){
                throw new Exception('设置字段迁移状态失败！');
            }
        }

        return true;
    }

    /**
     * 获取指定事物的查询条件字段
     *
     * @param int $thingId 事物编号
     * @return mixed
     */
    public function getThingContentQueryFields(int $thingId)
    {
        $fields = FieldModel::where('thing_id', $thingId)
            ->where('is_search', FieldModel::IS_SEARCH_YES)
            ->select(['id', 'name', 'show_type', 'show_options', 'comment'])
            ->get();

        return $this->normalizeReturn($fields);
    }

    /**
     * 获取事物内容列表
     *
     * @param int $thingId 事物编号
     * @param array $conditions 查询条件
     * @param int $offset 返回偏移量
     * @param int $limit 返回数量
     * @return array 事物内容列表
     * @throws Exception
     */
    public function getThingContents(int $thingId, array $conditions=[], int $offset=0, int $limit=0)
    {
        $thing = ThingModel::find($thingId);
        if(empty($thing)){
            throw new Exception('为找到对应的事物！');

        }else if(empty($thing['table_name'])){
            throw new Exception('该事物表不存在！');
        }


        $fields = FieldModel::where('thing_id', $thingId)
            ->where('is_list', FieldModel::IS_LIST_YES)
            ->select(['name', 'comment'])
            ->get();

        if(empty($fields)){
            throw new Exception('列表显示字段未配置！');
        }

        $listFields = ['id'];       // 默认返回ID编号
        $listComments = ['编号'];
        foreach ($fields as $field){
            $listFields[] = $field['name'];
            $listComments[] = $field['comment'];
        }

        $query = DB::table($thing['table_name'])->select($listFields)->orderBy('id', 'desc');
        foreach ($conditions as $condition){
            if(is_null($condition['value'])){
                continue;
            }
            if($condition['showType'] === FieldModel::SHOW_SELECT){
                $query->where($condition['name'], $condition['value']);

            }else if($condition['showType'] === FieldModel::SHOW_INPUT){
                if($condition['value'] === ''){
                    $query->where($condition['name'], $condition['value']);
                }else{
                    $query->where($condition['name'], 'like', '%'.$condition['value'].'%');
                }
            }else{
                $query->where($condition['name'], $condition['value']);
            }
        }

        if($limit > 0){
            $query->offset($offset)->limit($limit);
        }

        $return = [
            'title' => $listComments,
            'data' => $query->get()
        ];

        return $this->normalizeReturn($return);
    }

    /**
     * 获取事物内容
     *
     * @param int $thingId 事物编号
     * @param int $contentId 内容编号
     * @param array $fields 返回事物字段
     * @return array 事物内容
     * @throws Exception
     */
    public function getThingContent(int $thingId, int $contentId, array $fields=['*'])
    {
        $thing = ThingModel::findOrFail($thingId);
        if(! $thing->table_name){
            throw new Exception('未找到对于的表明！');
        }

        $content = DB::table($thing->table_name)
            ->where('id', $contentId)
            ->select($fields)
            ->first();

        return $this->normalizeReturn($content);
    }

    /**
     * 新增事物内容
     *
     * @param int $thingId 事物编号
     * @param array $contents 验证过的数据
     * @return array|false 成功返回事物内容，失败返回false
     * @throws Exception
     * @throws \Exception
     */
    public function addThingContent(int $thingId, array $contents)
    {
        $thing = ThingModel::find($thingId);
        if(! $thing){
            throw new Exception('未找到对应的事物！');

        }else if(! $thing->table_name){
            throw new \Exception('为找到对应的数据库表！');
        }

        $insertId = DB::table($thing->table_name)->insertGetId($contents);

        $content = DB::table($thing->table_name)->find($insertId);

        if(empty($content)){
            return false;
        }

        return $this->normalizeReturn($content);
    }

    /**
     * 更新事物内容
     *
     * @param int $thingId 事物编号
     * @param int $contentId 内容编号
     * @param array $contents 内容数组
     * @return bool|array 是否成功，成功则返回更新后的事物内容，失败返回false
     * @throws Exception
     * @throws \Exception
     */
    public function updateThingContent(int $thingId, int $contentId, array $contents)
    {
        $thing = ThingModel::find($thingId);
        if(! $thing){
            throw new Exception('未找到对应的事物！');

        }else if(! $thing->table_name){
            throw new \Exception('为找到对应的数据库表！');
        }

        $result = DB::table($thing->table_name)->where('id', $contentId)->update($contents);
        if(! $result){
            return false;
        }

        $content = DB::table($thing->table_name)->find($contentId);

        return $this->normalizeReturn($content);
    }

    /**
     * 删除事物内容
     *
     * @param int $thingId 事物编号
     * @param int $contentId 内容编号
     * @return bool 是否成功
     * @throws Exception
     * @throws \Exception
     */
    public function deleteThingContent(int $thingId, int $contentId)
    {
        $thing = ThingModel::find($thingId);
        if(! $thing){
            throw new Exception('未找到对应的事物！');

        }else if(! $thing->table_name){
            throw new \Exception('为找到对应的数据库表！');
        }

        $result = DB::table($thing->table_name)->delete($contentId);

        return $result ? true : false;
    }
}