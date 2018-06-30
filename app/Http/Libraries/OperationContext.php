<?php

namespace App\Http\Libraries;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/**
 * 用户操作上下文内容类
 *
 * Class SessionContext
 * @package App\Http\Libraries
 */
class OperationContext
{
    const CONTEXT_KEY        = '__C__';

    const CONTEXT_DATA = [
        'MENU_NAME'         => null,
        'APP_ID'            => null,
        'THING_ID'          => null,
        'BREADCRUMB_STACKS' => [],
    ];

    /**
     * @var array|null 动态菜单，如果当前菜单为动态菜单则设置，否则不设置
     */
    protected static $dynamicMenu;

    /**
     * @var string|null 动态菜单的，当前访问操作
     */
    protected static $dynamicAction;

    /**
     * 设置应用编号
     *
     * @param int $appId
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public static function setAppId(int $appId)
    {
        $session = static::commonGetContextData();
        $session['APP_ID'] = $appId;

        return static::commonSetContextData($session);
    }

    /**
     * 获取应用编号
     *
     * @return int|null
     */
    public static function getAppId()
    {
        $session = static::commonGetContextData();

        return $session['APP_ID'];
    }

    /**
     * 设置事物编号
     *
     * @param int $thingId
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public static function setThingId(int $thingId)
    {
        $session = static::commonGetContextData();
        $session['THING_ID'] = $thingId;

        return static::commonSetContextData($session);
    }

    /**
     * 获取事物编号
     *
     * @return int|null
     */
    public static function getThingId()
    {
        $session = static::commonGetContextData();

        return $session['THING_ID'];
    }

    /**
     * 替换字符串变量
     *
     * @param string $str 字符串
     * @return string 绑定后的字符串
     * @throws \Exception
     */
    public static function bindParams(string $str)
    {
        $contexts = static::commonGetContextData();
        foreach ($contexts as $key => $val){
            $name = '[' . $key . ']';
            if(strpos($str, $name) !== false){
                if(is_null($val)){
                    throw new \Exception('路由变量不存在！');
                }
                $str = str_replace($name, $val, $str);
            }
        }

        return $str;
    }

    /**
     * 获取当前访问节点数组
     *
     * @return array 访问节点数组
     */
    public static function getAccessNodes()
    {
        $context = static::commonGetContextData();

        return $context['BREADCRUMB_STACKS'];
    }

    /**
     * 获取最后一个访问节点
     *
     * @return array|null
     */
    public static function getLatestAccessNode()
    {
        $context = static::commonGetContextData();

        if(empty($context['BREADCRUMB_STACKS'])){
            return null;
        }

        return end($context['BREADCRUMB_STACKS']);
    }

    /**
     * 添加一个访问节点
     *
     * @param array $accessNode 访问节点 ['action'=>'ControllerAndAction']
     * @return array 最新的访问节点
     * @throws \Exception
     */
    public static function addAccessNode(array $accessNode)
    {
        if(empty($accessNode['name']) || empty($accessNode['action'])){
            throw new \Exception('添加访问节点参数错误！');
        }
        $accessNode = [
            'name'      => $accessNode['name'],
            'action'    => $accessNode['action'],
            'url'       => Request::fullUrl()
        ];

        $context = static::commonGetContextData();

        if(empty($context['BREADCRUMB_STACKS']) && $accessNode['name'] != '首页'){
            $context['BREADCRUMB_STACKS'] = [
                ['name'=>'首页', 'action'=>HomeController::class.'@index', 'url'=>'/']
            ];
        }
        $context['BREADCRUMB_STACKS'][] = $accessNode;


        static::commonSetContextData($context);

        return $accessNode;
    }

    /**
     * 设置访问节点
     *
     * @param array $accessNodes
     * @return array
     * @throws \Exception
     */
    public static function setAccessNodes(array $accessNodes)
    {
        $context = static::commonGetContextData();

        $context['BREADCRUMB_STACKS'] = $accessNodes;

        static::commonSetContextData($context);

        return $accessNodes;
    }

    /**
     * 重置访问节点数组
     *
     * @return array 访问节点数组
     */
    public static function resetAccessNodes()
    {
        $context = static::commonGetContextData();
        $context['BREADCRUMB_STACKS'] = [];

        static::commonSetContextData($context);

        return $context['BREADCRUMB_STACKS'];
    }

    /**
     * 获取当前用户菜单名称
     *
     * @return mixed
     */
    public static function getCurrentMenuName()
    {
        $context = static::commonGetContextData();

        return $context['MENU_NAME'];
    }

    /**
     * 设置当前用户菜单名
     *
     * @param string $name 菜单名称
     * @return string
     */
    public static function setCurrentMenuName(string $name)
    {
        $context = static::commonGetContextData();
        $context['MENU_NAME'] = $name;

        static::commonSetContextData($context);

        return $context['MENU_NAME'];
    }

    /**
     * 设置动态菜单
     *
     * @param array $menu 动态菜单
     * @return array
     */
    public static function setDynamicMenu(array $menu)
    {
        return self::$dynamicMenu = $menu;
    }

    /**
     * 获取动态菜单
     *
     * @return array|null
     */
    public static function getDynamicMenu()
    {
        return self::$dynamicMenu;
    }


    /**
     * 设置动态菜单的访问操作
     *
     * @param string $action 动态菜单的访问操作
     * @return string
     */
    public static function setDynamicAction(string $action)
    {
        return self::$dynamicAction = $action;
    }

    /**
     * 获取动态菜单的访问操作
     *
     * @return string|null
     */
    public static function getDynamicAction()
    {
        return self::$dynamicAction;
    }


    /**
     * 通用获取应用相关内容
     *
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    protected static function commonGetContextData()
    {
        return session(self::CONTEXT_KEY, self::CONTEXT_DATA);
    }

    /**
     * 通用设置应用相关内容
     *
     * @param $contextData
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    protected static function commonSetContextData($contextData)
    {
        return session([self::CONTEXT_KEY=>$contextData]);
    }
}