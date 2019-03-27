<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\Route\Route;
use CloverSwoole\CloverSwoole\Facade\Route\RouteInterface;
use CloverSwoole\CloverSwoole\Facade\Route\UrlParser;
use CloverSwoole\CloverSwoole\Facade\Whoops\WhoopsInterface;
use CloverSwoole\CloverSwoole\Framework;
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
     * @return mixed|void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function onRequest(Request $request_raw, Response $response_raw)
    {
        if(!Framework::exists_bind(\CloverSwoole\CloverSwoole\Facade\SwooleHttp\Request::class)){
            Framework::getContainerInterface()->bind(\CloverSwoole\CloverSwoole\Facade\Http\Request::class,\CloverSwoole\CloverSwoole\Facade\SwooleHttp\Request::class);
        }
        if(!Framework::exists_bind(\CloverSwoole\CloverSwoole\Facade\Http\Response::class)){
            Framework::getContainerInterface()->bind(\CloverSwoole\CloverSwoole\Facade\Http\Response::class,\CloverSwoole\CloverSwoole\Facade\SwooleHttp\Response::class);
        }
        if(!Framework::exists_bind(RouteInterface::class)){
            Framework::getContainerInterface()->bind(RouteInterface::class,\CloverSwoole\CloverSwoole\Facade\Route\Route::class);
        }
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
}