<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Route\Route;
use Itxiao6\Framework\Facade\Route\RouteInterface;
use Itxiao6\Framework\Facade\Route\UrlParser;
use Itxiao6\Framework\Facade\Whoops\WhoopsInterface;
use Swoole\Http\Response;
use Swoole\Http\Request;
use Whoops\Handler\PrettyPageHandler;

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
     * @param Request $request_raw
     * @param Response $response_raw
     */
    public function onRequest(Request $request_raw, Response $response_raw)
    {
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

    /**
     * 处理请求异常
     * @param \Throwable $throwable
     */
    protected function onRequestException(\Throwable $throwable,\Itxiao6\Framework\Facade\Http\Request $request,\Itxiao6\Framework\Facade\Http\Response $response)
    {
        $this -> container -> make(WhoopsInterface::class) -> onRequestException($throwable,$request,$response);
    }
}