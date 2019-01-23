<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\Route\Route;
use CloverSwoole\CloverSwoole\Facade\Route\RouteInterface;
use CloverSwoole\CloverSwoole\Facade\Route\UrlParser;
use CloverSwoole\CloverSwoole\Facade\Whoops\WhoopsInterface;
use Swoole\Http\Response;
use Swoole\Http\Request;
use Whoops\Handler\PrettyPageHandler;

/**
 * Http Swoole
 * Class HttpServer
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class HttpServer implements HttpServerInterface
{

    /**
     * 构造器
     * HttpServer constructor.
     */
    public function __construct()
    {


    }

    /**
     * 启动HttpServer
     */
    public function boot()
    {
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
         * @var \CloverSwoole\CloverSwoole\Facade\Http\Request $request
         */
        $request = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Request::class) -> boot($request_raw);
        /**
         * 设置全局访问
         */
        $request -> setAsGlobal();
        /**
         * 获取 response
         * @var \CloverSwoole\CloverSwoole\Facade\Http\Response $response
         */
        $response = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Response::class) -> boot($response_raw);
        /**
         * 设置全局访问
         */
        $response -> setAsGlobal();
        /**
         * 解析Url
         */
        $path = UrlParser::pathInfo($request -> getUri());
        /**
         * 启动路由组件
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(RouteInterface::class) -> boot($request,$response);
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
    protected function onRequestException(\Throwable $throwable,\CloverSwoole\CloverSwoole\Facade\Http\Request $request,\CloverSwoole\CloverSwoole\Facade\Http\Response $response)
    {
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(WhoopsInterface::class) -> onRequestException($throwable,$request,$response);
    }
}