<?php
namespace Itxiao6\Framework\Facade\Route;

/**
 * 静态路由
 * Class Statics
 * @package Itxiao6\Framework\Facade\Route
 */
class Statics implements StaticInterface
{
    /**
     * @var null | Route;
     */
    protected $route = null;

    /**
     * 获取控制器的基础命名空间
     * @return mixed
     */
    protected function getControllerBaseNameSpace()
    {
        return $this -> route -> getContainer()['config']['route']['controllerNameSpace'];
    }
    /**
     * 开始处理静态路由
     * @param Route $route
     */
    public function boot(Route $route)
    {
        $this -> route = $route;
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
        $controllerNameSpace = $this -> getControllerBaseNameSpace();
        /**
         * 静态(隐式)路由
         */
        if(!($route -> getContainer()['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC || $route -> getContainer()['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC_AND_DYNAMIC)){
            return ;
        }
        // 控制器名称
        $controller_name = $controllerNameSpace.$this -> getControllerName($this -> path);
        if(!class_exists($controller_name)){
            return;
        }
        // 操作名称
        $action_name = pathinfo($this -> path)['filename'];
        $controller = (new $controller_name()) -> __container($route -> getContainer()) -> __request($route -> getRequest()) -> __response($route -> getResponse()) -> __hook($action_name);

    }
    /**
     * 获取控制器名称
     * @param $path
     * @return string
     */
    protected function getControllerName($path)
    {
        return ltrim(str_replace('/','\\',pathinfo($path)['dirname']),'\\');
    }
}