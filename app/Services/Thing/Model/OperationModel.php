<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

/**
 * 操作模型
 * App\Services\Thing\Model\OperationModel
 *
 * @mixin \Eloquent
 */
class OperationModel extends Model
{
    protected $table = 'thing_operations';
    protected $fillable = ['thing_id', 'name', 'operation_type', 'operation_form'];

    protected $appends = ['perm_code'];

    public const OPERATION_TYPE_ADD         = 'add';
    public const OPERATION_TYPE_DELETE      = 'delete';
    public const OPERATION_TYPE_UPDATE      = 'update';
    public const OPERATION_TYPE_SELECT      = 'select';

    public const OPERATION_FORM_COMMAND     = 'command';    // 直接列表操作
    public const OPERATION_FORM_FORM        = 'form';       // 表单修改操作

    protected const OPERATION_TYPE_MAP = [
        self::OPERATION_TYPE_ADD      => '新增',
        self::OPERATION_TYPE_DELETE   => '删除',
        self::OPERATION_TYPE_UPDATE   => '修改',
        self::OPERATION_TYPE_SELECT   => '查询',
    ];

    protected const OPERATION_FORM_MAP = [
        self::OPERATION_FORM_FORM           => '表单',
        self::OPERATION_FORM_COMMAND        => '命令',
    ];

    /**
     * 获取操作类型
     *
     * @return array
     */
    public static function getOperationTypeMap()
    {
        return self::OPERATION_TYPE_MAP;
    }

    /**
     * 获取操作形式
     *
     * @return array
     */
    public static function getOperationFormMap()
    {
        return self::OPERATION_FORM_MAP;
    }

    /**
     * 操作对应的字段定义
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(OperationFieldModel::class, 'operation_id');
    }

    /**
     * 获取权限码
     *
     * @return null|string
     */
    public function getPermCodeAttribute()
    {
        if(! $this->id){
            return null;
        }
        return self::encodePermCode($this->thing_id, $this->id);
    }

    /**
     * 编码权限码
     *
     * @param int $thingId 事物编号
     * @param int $operationId 操作编号
     * @return string
     */
    public static function encodePermCode(int $thingId, int $operationId)
    {
        return "Thing_{$thingId}_{$operationId}";
    }

    /**
     * 解码权限码
     *
     * @param string $permCode 权限码
     * @return array|bool
     */
    public static function decodePermCode(string $permCode)
    {
        if(! preg_match('/^Thing_(\d+)_(\d+)$/', $permCode, $matches)){
            return false;
        }

        return [
            'thingId' => $matches[1],
            'operationId' => $matches[2],
        ];
    }
}
