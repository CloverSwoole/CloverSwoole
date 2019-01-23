<?php
namespace CloverSwoole\CloverSwoole\Facade\Route;

/**
 * 路由配置
 * Class Config
 * @package CloverSwoole\CloverSwoole\Facade\Route
 */
class Config implements ConfigInterface
{
    /**
     * 动态路由
     */
    CONST ROUTE_TYPE_DYNAMIC = 3;
    /**
     * 动静 兼容模式
     */
    CONST ROUTE_TYPE_STATIC_AND_DYNAMIC = 2;
    /**
     * 静态路由
     */
    CONST ROUTE_TYPE_STATIC = 1;

    /**
     * @return mixed|void
     */
    public function boot()
    {
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['route'] = [
            // 路由类型
            'routeType'=>self::ROUTE_TYPE_STATIC_AND_DYNAMIC,
            // 动态路由表
            'route_dynamic_lists'=>[],
            // 控制器的记录路径
            'controllerNameSpace'=>'App\\Http\\Controller\\',
            // 默认控制器名称
            'defaultControllerName'=>'Index',
            // 默认操作名称
            'defaultActionName'=>'index',
            // 进程控制器数量
            'maxPoolNum'=>15,
            // 最大深度
            'maxDepth'=>5,
        ];
    }
}