<?php
namespace Itxiao6\Framework\Facade\Route;

use Illuminate\Container\Container;

/**
 * 路由配置
 * Class Config
 * @package Itxiao6\Framework\Facade\Route
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
     * @param Container|null $container
     * @return mixed|void
     */
    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $container['config']['route'] = [
            // 路由类型
            'routeType'=>self::ROUTE_TYPE_STATIC_AND_DYNAMIC,
            // 动态路由表
            'route_dynamic_lists'=>[],
            // 控制器的记录路径
            'controllerNameSpace'=>'App\\Http\\Controller\\',
            // 进程控制器数量
            'maxPoolNum'=>15,
            // 最大深度
            'maxDepth'=>5,
        ];
    }
}