<?php
namespace Itxiao6\Framework\Facade\Route;

use EasySwoole\Http\AbstractInterface\Controller;
use Itxiao6\Framework\Facade\Http\Exception\NotFoundController;

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
     * @var string
     */
    protected $path = '';
    /**
     * @var string
     */
    protected $method = 'GET';
    /**
     * 获取控制器的基础命名空间
     * @return mixed
     */
    protected function getControllerBaseNameSpace()
    {
        return $this -> route -> getContainer()['config']['route']['controllerNameSpace'];
    }
    /**
     * 获取默认控制器的名称
     * @return mixed
     */
    protected function getDefaultControllerName()
    {
        return $this -> route -> getContainer()['config']['route']['defaultControllerName'];
    }
    /**
     * 获取默认操作的名称
     * @return mixed
     */
    protected function getDefaultActionName()
    {
        return $this -> route -> getContainer()['config']['route']['defaultActionName'];
    }
    /**
     * 开始处理静态路由
     * @param Route $route
     */
    public function boot(Route $route)
    {
        $this -> route = $route;
        /**
         * 静态(隐式)路由
         */
        if(!($route -> getContainer()['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC || $route -> getContainer()['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC_AND_DYNAMIC)){
            return ;
        }
        /**
         * 获取请求uri
         */
        $this -> path = $this -> route -> getRequest() -> getUri();
        /**
         * 获取请求的方法
         */
        $this -> method = $this -> route -> getRequest() -> getRequestMethod();
        try{
            $this -> makeController() -> __hook($this -> getActionName());
        }catch (\Throwable $throwable){
            dump($throwable);
        }
    }
    /**
     * 获取操作名称
     * @return mixed
     */
    protected function getActionName()
    {
        $action_name = pathinfo($this -> path)['filename'];
        if($action_name == ''){
            return $this -> getDefaultActionName();
        }
        return $action_name;
    }
    /**
     * 获取控制器名称
     * @return string
     */
    protected function getControllerClass()
    {
        /**
         * 获取控制器基础命名空间
         */
        $controllerNameSpace = $this -> getControllerBaseNameSpace();
        /**
         * 控制器名称
         */
        $controller_name = $controllerNameSpace.$this -> getControllerName($this -> path);
        /**
         * 判断控制器是否存在
         */
        if(!class_exists($controller_name)){
            $controller_name = rtrim($controller_name,'\\').'\\'.$this -> getDefaultControllerName();
            if(!class_exists($controller_name)){
                // 抛出异常
                throw new NotFoundController("找不到:{$controller_name} 控制器。");
            }
        }
        return $controller_name;
    }
    /**
     * 创建控制器
     * @return mixed|Controller
     */
    protected function makeController()
    {
        $class = $this -> getControllerClass();
        return new $class($this -> route);
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