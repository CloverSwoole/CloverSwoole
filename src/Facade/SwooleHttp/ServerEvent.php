<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\Whoops\WhoopsInterface;
use CloverSwoole\CloverSwoole\Framework;
/**
 * 服务事件模型
 * Class ServerEvent
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class ServerEvent implements ServerEventInterface
{

    /**
     * 获取容器
     * @return $this
     */
    public function boot()
    {
        return $this;
    }
    /**
     * 服务启动
     * @param \Swoole\Http\Server $server
     * @return mixed|void
     */
    public function onStart(\Swoole\Http\Server $server)
    {
        echo "WebServerOnStarted\n";
    }
    /**
     * 消息到达
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     * @return mixed|void
     */
    public function onRequest(\swoole_http_request $request_raw, \swoole_http_response $response_raw)
    {
        try{
            /**
             * 实例化WebServer
             */
            $http_service = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(HttpServerInterface::class) -> boot();
            /**
             * 请求到达
             */
            $http_service -> onRequest($request_raw,$response_raw);
        }catch (\Throwable $exception){
            /**
             * 获取 request
             */
            $request = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Request::class) -> boot($request_raw);
            /**
             * 获取 response
             */
            $response = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Response::class) -> boot($response_raw);
            /**
             * 处理异常
             */
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(WhoopsInterface::class) -> swooleOnRequestException($exception,$request,$response);
        }
    }

    /**
     * 服务关闭
     * @param \Swoole\Http\Server $server
     * @return mixed|void
     */
    public function onShutdown(\Swoole\Http\Server $server)
    {
        echo "WebServerOnShutdownEd\n";
    }
    /**
     * 服务关闭
     * @param \Swoole\Http\Server $server
     * @param int $fd
     * @return mixed|void
     */
    public function onClose(\Swoole\Http\Server $server,$fd)
    {
        echo "WebServer Closed\n";
    }

    /**
     * 服务关闭
     * @param \Swoole\Http\Server $server
     * @param \Swoole\Http\Server $request
     * @return mixed|void
     */
    public function onOpen(\Swoole\Http\Server $server, \Swoole\Http\Server $request)
    {
        echo "WebServerOpOpened\n";
    }
}