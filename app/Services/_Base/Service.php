<?php
/**
 * Created by PhpStorm.
 * User: Tipine
 * Date: 2018/5/31
 * Time: 11:22
 */

namespace App\Services\_Base;

/**
 * 服务层基类
 * 1. 主要为控制器层提供服务数据（简单数据类型：字符串、数组、数字、布尔值）
 * 2. 统筹业务逻辑和业务数据
 * 3. 抛出的异常需要控制器层捕获
 * 4. 职责包括服务的逻辑验证
 *
 * Class Service
 * @package App\Services\_Base
 */
abstract class Service
{
    /**
     * 使用单例模式
     */
    use SingletonTrait;

    /**
     * 解析调用授权码
     *
     * @param $authCode
     * @return mixed
     */
    public function parseAuthCode($authCode)
    {
        return $authCode;
    }

}