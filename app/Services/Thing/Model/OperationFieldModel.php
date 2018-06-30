<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 操作字段模型
 *
 * App\Services\Thing\Model\OperationFieldModel
 *
 * @mixin \Eloquent
 */
class OperationFieldModel extends Model
{
    protected $table = 'thing_operation_fields';
    protected $fillable = ['operation_id', 'field_id', 'is_show', 'update_type'];

    const IS_SHOW_YES   = 1;
    const IS_SHOW_NO    = 0;

    protected const MAP_IS_SHOW = [
        self::IS_SHOW_YES   => '是',
        self::IS_SHOW_NO    => '否',
    ];

    const UPDATE_TYPE_NOT_UPDATE   = 'not_update';
    const UPDATE_TYPE_USER_INPUT      = 'user_input';
    const UPDATE_TYPE_CURRENT_USER    = 'current_user';
    const UPDATE_TYPE_CURRENT_TIME    = 'current_time';

    protected const MAP_UPDATE_TYPE = [
        self::UPDATE_TYPE_NOT_UPDATE   => '无操作',
        self::UPDATE_TYPE_USER_INPUT      => '用户输入',
        self::UPDATE_TYPE_CURRENT_USER    => '当前用户',
        self::UPDATE_TYPE_CURRENT_TIME    => '当前时间',
    ];

    public static function getUpdateTypeMap()
    {
        return self::MAP_UPDATE_TYPE;
    }

    /**
     * 获取是否显示隐射
     *
     * @return array
     */
    public static function getIsShowMap()
    {
        return self::MAP_IS_SHOW;
    }

    /**
     * 设置访问器
     *
     * @param $value
     * @return bool
     */
    public function getIsShowAttribute($value)
    {
        return ($value==self::IS_SHOW_YES) ? true : false;
    }

    /**
     * 设置修改器
     *
     * @param $value
     * @return int
     */
    public function setIsShowAttribute($value)
    {
        return $this->attributes['is_show'] = ($value==true) ? self::IS_SHOW_YES : self::IS_SHOW_NO;
    }

}
