<?php
/**
 * Created by PhpStorm.
 * User: Tipine
 * Date: 2018/5/31
 * Time: 11:21
 */

namespace App\Services\_Base;

/**
 * 业务逻辑层基类
 * 1. 为服务层提供复杂的数据更新操作，保证单元逻辑原子性（比如：更新数据需要多个步骤，涉及事务）
 * 2. 为服务层提供复杂的业务逻辑操作
 * 3. 只是数据仓库的逻辑包装，不能直接包含模型层
 *
 * Class Repository
 * @package App\Services\_Base
 */
abstract class Logic
{
    /**
     * 使用单例模式
     */
    use SingletonTrait;

}