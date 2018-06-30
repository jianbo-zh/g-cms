<?php
/**
 * Created by PhpStorm.
 * User: Tipine
 * Date: 2018/5/31
 * Time: 11:21
 */

namespace App\Services\_Base;
use Illuminate\Contracts\Support\Arrayable;

/**
 * 数据层基类
 * 1. 主要提供为服务层或逻辑层提供基本数据（简单数据类型：字符串、数组、数字、布尔值）
 * 2. 服务层所有数据都只能通过数据层来获取，不能直接调模型
 * 3. 备注：返回的数组KEY需转换成小驼峰形式
 * 4. 只返回更新、删除数据，提供数据层验证，并保证数据有效性
 *
 * Class Repository
 * @package App\Services\_Base
 */
abstract class Repository
{
    /**
     * 使用单例模式
     */
    use SingletonTrait;

    /**
     * 规范化数据层返回数据
     * 1. 如果返回是数组，则把下划线键转换成小驼峰键
     *
     * @param mixed 返回数据
     * @return mixed
     */
    protected function normalizeReturn($data)
    {
        if($data instanceof Arrayable){
            $data = self::switchArrayToCamelKey($data->toArray());

        }else if(is_array($data)){
            $data = self::switchArrayToCamelKey($data);

        }else if(is_object($data) && get_class($data) === 'stdClass'){
            $data = self::switchArrayToCamelKey(json_decode(json_encode($data), true));

        }else{
            return $data;
        }

        foreach ($data as $key => $value){
            if(
                $value instanceof Arrayable ||
                is_array($value) ||
                (is_object($value) && get_class($value) === 'stdClass')
            ){
                $data[$key] = $this->normalizeReturn($value);
            }
        }

        return $data;
    }

    /**
     * 把下划线KEY数组转换成小驼峰KEY数组
     * 主要完成数据库字段名到程序变量名的转换
     *
     * @param array $array 含下划线KEY的数组
     * @return array 小驼峰KEY的数组
     */
    protected static function switchArrayToCamelKey(array $array)
    {
        $newArray = [];
        foreach ($array as $key => $value){
            if(is_string($key)){
                while(($pos = strpos($key , '_')) !== false){
                    $key = substr($key , 0 , $pos) . ucfirst(substr($key , $pos+1));
                }
            }
            if (is_array($value)){
                $value = self::switchArrayToCamelKey($value);
            }
            $newArray[$key] = $value;
        }
        return $newArray;
    }

}