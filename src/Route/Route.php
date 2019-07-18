<?php
namespace CloverSwoole\Route;

use CloverSwoole\Exception\ExceptionHandler;
use CloverSwoole\Http\Request;

/**
 * 路由组件实例
 * Class Route
 * @package CloverSwoole\Route
 */
class Route
{
    /**
     * @var null | RouteConfig
     */
    protected $config = null;

    /**
     * 获取路由实例
     * @return Route
     * @throws \Exception
     */
    public static function getInterface()
    {
        try{
            return (new static(...func_get_args()));
        }catch (\Throwable $throwable){
            ExceptionHandler::getInterface() -> catchRoute($throwable);
        }
    }

    /**
     * 路由实例构造器
     * Route constructor.
     */
    protected function __construct()
    {
        $this -> config = RouteConfig::getInterface();
        /**
         * 获取控制器名称
         */
        $controller_name = $this -> getControllerName(Request::getInterface() -> getUri());
        /**
         * 获取控制器命名空间前缀
         */
        $controller_name_space = $this -> config -> getConfig('controllerNameSpace');
        /**
         * 判断控制器是否存在
         */
        if(!class_exists($controller_name_space.$controller_name)){
            throw new \Exception('控制器:'.$controller_name.' 不存在');
        }
        /**
         * 拼接类名
         */
        $class = $controller_name_space.$controller_name;
        /**
         * 实例化控制器
         */
        new $class($this -> getAction(Request::getInterface() -> getUri()));
    }
    /**
     * 获取控制器名称
     * @param $path
     * @return string
     */
    protected function getControllerName($path)
    {
        if(pathinfo($path)['dirname'] == '/' || pathinfo($path)['dirname'] == ''){
            return $this -> config -> getConfig('defaultControllerName');
        }
        return ltrim(str_replace('/','\\',pathinfo($path)['dirname']),'\\');
    }

    /**
     * 获取操作名称
     * @param $path
     * @return mixed|null
     */
    protected function getAction($path)
    {
        if(pathinfo($path)['filename'] == '/' || pathinfo($path)['filename'] == ''){
            return $this -> config -> getConfig('defaultActionName');
        }
        return pathinfo($path)['filename'];
    }

}