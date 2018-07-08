<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Services\Thing\Model\FieldModel
 *
 * @mixin \Eloquent
 */
class FieldModel extends Model
{
    protected $table = 'thing_fields';
    protected $fillable = ['thing_id', 'name', 'name_old', 'storage_type', 'show_type', 'show_options', 'is_list',
        'is_search', 'comment', 'state'];

    const STATE_ADD             = 1;    // 新增的字段
    const STATE_UPDATE          = 2;    // 修改了字段
    const STATE_MIGRATED        = 4;    // 已经迁移了该字段

    /**
     * 数据类型定义
     */
    const STORAGE_INT_TINYINT      = 'tinyint';
    const STORAGE_SMALLINT         = 'smallint';
    const STORAGE_MEDIUMINT        = 'mediumint';
    const STORAGE_INT              = 'int';
    const STORAGE_BIGINT           = 'bigint';
    const STORAGE_FLOAT            = 'float';
    const STORAGE_DOUBLE           = 'double';
    const STORAGE_DECIMAL82        = 'decimal82';
    const STORAGE_VARCHAR_10       = 'varchar10';
    const STORAGE_VARCHAR_20       = 'varchar20';
    const STORAGE_VARCHAR_60       = 'varchar60';
    const STORAGE_VARCHAR_100      = 'varchar100';
    const STORAGE_VARCHAR_150      = 'varchar100';
    const STORAGE_VARCHAR_255      = 'varchar255';
    const STORAGE_VARCHAR_1000     = 'varchar1000';
    const STORAGE_DATE             = 'date';
    const STORAGE_DATETIME         = 'datetime';
    const STORAGE_TIMESTAMP        = 'timestamp';
    const STORAGE_BLOB             = 'blob';
    const STORAGE_TEXT             = 'text';

    protected const MAP_STORAGE_TYPE = [
        self::STORAGE_INT_TINYINT      => 'TINYINT',
        self::STORAGE_SMALLINT         => 'SMALLINT',
        self::STORAGE_MEDIUMINT        => 'MEDIUMINT',
        self::STORAGE_INT              => 'INT',
        self::STORAGE_BIGINT           => 'BIGINT',
        self::STORAGE_FLOAT            => 'FLOAT',
        self::STORAGE_DOUBLE           => 'DOUBLE',
        self::STORAGE_DECIMAL82        => 'DECIMAL(8,2)',
        self::STORAGE_VARCHAR_10       => 'VARCHAR(10)',
        self::STORAGE_VARCHAR_20       => 'VARCHAR(20)',
        self::STORAGE_VARCHAR_60       => 'VARCHAR(60)',
        self::STORAGE_VARCHAR_100      => 'VARCHAR(100)',
        self::STORAGE_VARCHAR_150      => 'VARCHAR(150)',
        self::STORAGE_VARCHAR_255      => 'VARCHAR(255)',
        self::STORAGE_VARCHAR_1000     => 'VARCHAR(1000)',
        self::STORAGE_DATE             => 'DATE',
        self::STORAGE_DATETIME         => 'DATETIME',
        self::STORAGE_TIMESTAMP        => 'TIMESTAMP',
        self::STORAGE_BLOB             => 'BLOB',
        self::STORAGE_TEXT             => 'TEXT',
    ];

    const SHOW_RADIO            = 'radio';      // [{name:'男', value:1}, ...]
    const SHOW_CHECKBOX         = 'checkbox';   // {name:'男', value:1}
    const SHOW_SELECT           = 'select';     // [{name:'男', value:1}, ...]
    const SHOW_INPUT            = 'input';
    const SHOW_TEXTAREA         = 'textarea';
    const SHOW_RICHTEXT         = 'richtext';

    protected const MAP_SHOW_TYPE = [
        self::SHOW_RADIO        => 'RADIO',
        self::SHOW_CHECKBOX     => 'CHECKBOX',
        self::SHOW_SELECT       => 'SELECT',
        self::SHOW_INPUT        => 'INPUT',
        self::SHOW_TEXTAREA     => 'TEXTAREA',
        self::SHOW_RICHTEXT     => 'RICHTEXT',
    ];


    protected $casts = [
        'show_options' => 'array',
    ];


    /**
     * 获取所有的存储类型
     * @return array
     */
    public static function getStorageTypes()
    {
        return self::MAP_STORAGE_TYPE;
    }

    /**
     * 获取所有的展示类型
     *
     * @return array
     */
    public static function getShowTypes()
    {
        return self::MAP_SHOW_TYPE;
    }

    /**
     * 是否列表显示
     */
    public const IS_LIST_YES    = 1;
    public const IS_LIST_NO     = 0;

    protected const MAP_IS_LIST = [
        self::IS_LIST_YES   => '是',
        self::IS_LIST_NO    => '否',
    ];

    /**
     * 获取列表隐射关系
     *
     * @return array
     */
    public static function getIsListMap()
    {
        return self::MAP_IS_LIST;
    }

    /**
     * 是否搜索条件
     */
    public const IS_SEARCH_YES    = 1;
    public const IS_SEARCH_NO     = 0;
    protected const MAP_IS_SEARCH = [
        self::IS_SEARCH_YES   => '是',
        self::IS_SEARCH_NO    => '否',
    ];

    /**
     * 获取搜索隐射关系
     *
     * @return array
     */
    public static function getIsSearchMap()
    {
        return self::MAP_IS_SEARCH;
    }

    /**
     * 定义是否列表显示访问器
     *
     * @param $value
     * @return bool
     */
    public function getIsListAttribute($value)
    {
        return ($value == self::IS_LIST_YES) ? true : false;
    }

    /**
     * 定义是否搜索条件显示访问器
     *
     * @param $value
     * @return bool
     */
    public function getIsSearchAttribute($value)
    {
        return ($value == self::IS_SEARCH_YES) ? true : false;
    }

    /**
     * 定义是否列表显示修改器
     *
     * @param $value
     * @return int
     */
    public function setIsListAttribute($value)
    {
        return $this->attributes['is_list'] = ($value == true) ? self::IS_LIST_YES : self::IS_LIST_NO;
    }

    /**
     * 定义是否搜索显示修改器
     *
     * @param $value
     * @return int
     */
    public function setIsSearchAttribute($value)
    {
        return $this->attributes['is_search'] = ($value == true) ? self::IS_SEARCH_YES : self::IS_SEARCH_NO;
    }

}
