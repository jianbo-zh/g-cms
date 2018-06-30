<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Services\Thing\Model\StateConditionModel
 *
 * @mixin \Eloquent
 */
class StateConditionModel extends Model
{
    protected $table = 'thing_state_conditions';
    protected $fillable = ['state_id', 'field_id', 'symbol', 'value'];

    const SYMBOL_EQ         = 'EQ';
    const SYMBOL_NEQ        = 'NEQ';
    const SYMBOL_GT         = 'GT';
    const SYMBOL_LT         = 'LT';
    const SYMBOL_EGT        = 'EGT';
    const SYMBOL_ELT        = 'ELT';
    const SYMBOL_NULL       = 'NULL';
    const SYMBOL_NOT_NULL   = 'NOT NULL';
    const SYMBOL_BETWEEN    = 'BETWEEN';
    const SYMBOL_IN         = 'IN';
    const SYMBOL_EQ_FIELD   = 'FIELD';

    protected const MAP_SYMBOL_TEXT = [
        self::SYMBOL_EQ         => '等于',
        self::SYMBOL_NEQ        => '不等于',
        self::SYMBOL_GT         => '大于',
        self::SYMBOL_LT         => '小于',
        self::SYMBOL_EGT        => '大于或等于',
        self::SYMBOL_ELT        => '小于或等于',
        self::SYMBOL_NULL       => '为空',
        self::SYMBOL_NOT_NULL   => '不为空',
        self::SYMBOL_BETWEEN    => '在值之间',
        self::SYMBOL_IN         => '包含值',
        self::SYMBOL_EQ_FIELD   => '等于字段',
    ];

    public static function getSymbolMap()
    {
        return self::MAP_SYMBOL_TEXT;
    }

}
