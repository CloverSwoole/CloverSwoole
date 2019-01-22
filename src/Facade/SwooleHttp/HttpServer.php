<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use Illuminate\Container\Container;
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
        $request = $this -> container -> make(\CloverSwoole\CloverSwoole\Facade\Http\Request::class) -> boot($request_raw);
        /**
         * 设置全局访问
         */
        $request -> setAsGlobal();
        /**
         * 获取 response
         */
        $response = $this -> container -> make(\CloverSwoole\CloverSwoole\Facade\Http\Response::class) -> boot($response_raw);
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
    protected function onRequestException(\Throwable $throwable,\CloverSwoole\CloverSwoole\Facade\Http\Request $request,\CloverSwoole\CloverSwoole\Facade\Http\Response $response)
    {
        $this -> container -> make(WhoopsInterface::class) -> onRequestException($throwable,$request,$response);
    }
}