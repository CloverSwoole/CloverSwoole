<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Route\Route;
use Itxiao6\Framework\Facade\Route\RouteInterface;
use Itxiao6\Framework\Facade\Route\UrlParser;

/**
 * Http Swoole
 * Class HttpServer
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class HttpServer implements HttpServerInterface
{
    /**
     * 容器
     * @var null | Container
     */
    protected $container = null;

    /**
     * 构造器
     * HttpServer constructor.
     */
    public function __construct()
    {


    }

    /**
     * 启动HttpServer
     * @param Container|null $container
     */
    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $this -> container = $container;
        return $this;
    }

    /**
     * 请求到达时
     * @param \swoole_http_request $request_raw
     * @param \swoole_http_response $response_raw
     */
    public function onRequest(\swoole_http_request $request_raw, \swoole_http_response $response_raw)
    {
        /**
         * 注入依赖
         */
        $this -> container -> bind(\Itxiao6\Framework\Facade\Http\Request::class,Request::class);
        /**
         * 注入依赖
         */
        $this -> container -> bind(\Itxiao6\Framework\Facade\Http\Response::class,Response::class);

        /**
         * 获取 request
         */
        $request = $this -> container -> make(\Itxiao6\Framework\Facade\Http\Request::class) -> boot($request_raw);
        /**
         * 获取 response
         */
        $response = $this -> container -> make(\Itxiao6\Framework\Facade\Http\Response::class) -> boot($response_raw);
        /**
         * 解析Url
         */
        $path = UrlParser::pathInfo($request -> getUri());
        /**
         * 注入路由组件
         */
        $this -> container -> bind(RouteInterface::class,Route::class);
        /**
         * 启动路由组件
         */
        $this -> container -> make(RouteInterface::class) -> boot($request,$response,$this -> container);
        /**
         * 如果没有结束响应则 后置结束
         */
        if(!$response->ResponseIsEnd()){
            $response->endResponse();
        }
    }
}