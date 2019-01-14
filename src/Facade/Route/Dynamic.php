<?php
namespace Itxiao6\Framework\Facade\Route;

/**
 * 动态路由
 * Class Dynamic
 * @package Itxiao6\Framework\Facade\Route
 */
class Dynamic implements DynamicInterface
{
    /**
     * 开始处理动态路由
     * @param Route $route
     */
    public function boot(Route $route)
    {
        /**
         * 获取请求uri
         */
        $this -> path = $route -> getRequest() -> getUri();
        /**
         * 获取请求的方法
         */
        $this -> method = $route -> getRequest() -> getRequestMethod();
        /**
         * 获取控制器基础命名空间
         */
        $controllerNameSpace = $route -> getContainer()['config']['route']['controllerNameSpace'];
        /**
         * 动态路由表
         */
        if(!($route -> getContainer()['config']['route']['routeType'] == Config::ROUTE_TYPE_DYNAMIC || $route -> getContainer()['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC_AND_DYNAMIC)){
            return ;
        }

    }
}