<?php
/**
 * Created by PhpStorm.
 * User: Tipine
 * Date: 2018/5/28
 * Time: 15:53
 */

namespace App\Services\_Base;

/**
 * 单例模式
 * Trait SingletonTrait
 * @package App\Services\_Base
 */
Trait SingletonTrait
{
    private static $instance=[];

    /**
     * 通过延迟加载（用到时才加载）获取实例
     *
     * @return static
     */
    public static function instance()
    {
        $className = get_called_class();

        if(!isset(self::$instance[$className])){
            self::$instance[$className] = new $className();
        }

        return self::$instance[$className];
    }

    /**
     * 构造函数私有，不允许在外部实例化
     *
     */
    private function __construct()
    {
    }

    /**
     * 防止对象实例被克隆
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * 防止被反序列化
     *
     * @return void
     */
    private function __wakeup()
    {
    }

}