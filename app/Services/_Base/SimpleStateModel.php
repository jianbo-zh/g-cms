<?php
/**
 * Created by PhpStorm.
 * User: Tipine
 * Date: 2018/5/28
 * Time: 15:53
 */

namespace App\Services\_Base;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StateModel
 * @package App\Services\_Base
 */
abstract class SimpleStateModel extends Model
{
    public const STATE_ENABLE  = 1;     // 启用状态
    public const STATE_DISABLE = 0;     // 禁用状态
    protected const MAP_STATE = [
        self::STATE_ENABLE      => '启用',
        self::STATE_DISABLE     => '禁用'
    ];

    /**
     * 获取状态的说明文字
     *
     * @param int $state 状态值
     * @return string 状态文字
     */
    public static function getStateText(int $state)
    {
        if(!in_array($state, [self::STATE_ENABLE, self::STATE_DISABLE])){
            throw new \InvalidArgumentException('状态错误！');
        }

        return self::MAP_STATE[$state];
    }

    /**
     * 获取状态映射
     *
     * @return array
     */
    public static function getStateMap()
    {
        return self::MAP_STATE;
    }
}