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
class Route implements RouteInterface
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
     * 路由是否 已经处理完毕
     * @var bool
     */
    protected $is_end = false;
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
     * 获取响应
     * @return Response|null
     */
    public function getResponse()
    {
        return $this -> response;
    }
    /**
     * 获取请求
     * @return Request|null
     */
    public function getRequest()
    {
        return $this -> request;
    }
    /**
     * 结束路由处理
     */
    public function endRoute()
    {
        $this -> is_end = true;
    }
    /**
     * 路由处理是否已经处理完毕
     * @return bool
     */
    public function routeIsEnd()
    {
        return $this -> is_end;
    }
    /**
     * 启动组件
     * @param Request $request
     * @param Response $response
     * @param Container|null $container
     */
    public function boot(Request $request,Response $response,?Container $container)
    {
        if(!($container instanceof Container) ){
            $container = new Container();
        }
        /**
         * 注入容器
         */
        $this -> container = $container;
        /**
         * 注入请求
         */
        $this -> request = $request;
        /**
         * 注入响应
         */
        $this -> response = $response;
        /**
         * 获取路由配置
         */
        $this -> container -> make(ConfigInterface::class) -> boot($this -> container);
        /**
         * 获取动态路由
         */
        $this -> container -> make(DynamicInterface::class) -> boot($this);
        /**
         * 判断路由是否已经结束
         */
        if($this -> routeIsEnd()){
            return ;
        }
        /**
         * 静态路由
         */
        $this -> container -> make(StaticInterface::class) -> boot($this);
    }
    /**
     * 获取容器
     * @return Container|null
     */
    public function getContainer()
    {
        return $this -> container;
    }
}