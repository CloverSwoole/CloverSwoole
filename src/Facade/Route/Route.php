<?php
namespace Itxiao6\Framework\Facade\Route;

use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Http\Request;
use Itxiao6\Framework\Facade\Http\Response;

/**
 * 路由组件实例
 * Class Route
 * @package Itxiao6\Framework\Facade\Route
 */
class Route
{
    /**
     * 请求路径
     * @var string
     */
    protected $path = '/';
    /**
     * 请求方法
     * @var string
     */
    protected $method = '';
    /**
     * 服务容器
     * @var null |Container
     */
    protected $container = null;
    /**
     * @var null |Request
     */
    protected $request = null;
    /**
     * @var null|Response
     */
    protected $response = null;

    /**
     * 动态路由表
     * @var array
     */
    protected $route_dynamic_lists = [];

    /**
     * 构造器
     * Route constructor.
     */
    public function __construct()
    {

    }

    /**
     * 设置容器
     * @param Container|null $container
     * @return $this
     */
    public function container(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $this -> container = $container;
        return $this;
    }

    /**
     * 设置响应 句柄
     * @param Response $response
     * @return $this
     */
    public function response(Response $response)
    {
        $this -> response = $response;
        return $this;
    }

    /**
     * 设置请求 句柄
     * @param Request $request
     * @return $this
     */
    public function request(Request $request)
    {
        $this -> request = $request;
        return $this;
    }

    /**
     * 启动组件
     * @param $path
     * @param $method
     */
    public function boot($path,$method)
    {
        /**
         * 注入路由配置
         */
        $this -> container -> bind(ConfigInterface::class,Config::class);
        /**
         * 获取路由配置
         */
        $this -> container -> make(ConfigInterface::class) -> boot($this -> container);

        /**
         * 获取动态路由
         */
        $this -> route_dynamic_lists = $this -> container['config']['route']['route_dynamic_lists'];
        $this -> path = $path;
        $this -> method = $method;
        $controllerNameSpace = $this -> container['config']['route']['controllerNameSpace'];
        /**
         * 静态(隐式)路由
         */
        if($this -> container['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC || $this -> container['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC_AND_DYNAMIC){
            // 控制器名称
            $controller_name = $controllerNameSpace.$this -> getControllerName($this -> path);
            if(!class_exists($controller_name)){
                echo "控制器不存在\n";
                return;
            }
            // 操作名称
            $action_name = pathinfo($this -> path)['filename'];
            $controller = (new $controller_name()) -> __container($this -> container) -> __request($this -> request) -> __response($this -> response) -> __hook($action_name);
        }
        /**
         * 动态路由表
         */
        if($this -> container['config']['route']['routeType'] == Config::ROUTE_TYPE_DYNAMIC || $this -> container['config']['route']['routeType'] == Config::ROUTE_TYPE_STATIC_AND_DYNAMIC){

        }
    }
    protected function getControllerName($path)
    {
        return ltrim(str_replace('/','\\',pathinfo($path)['dirname']),'\\');
    }
}