<?php
namespace App\Event;
use CloverSwoole\CloverSwoole\Facade\SuperClosure\SuperClosure;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerEvent;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManageInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\HttpServerInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\SwooleHttpInterface;
use CloverSwoole\CloverSwoole\Facade\Whoops\WhoopsInterface;

/**
 * 服务事件模型
 * Class SwooleHttpEvent
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class SwooleHttpEvent extends ServerEvent implements SwooleHttpInterface
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
        /**
         * 获取全局Server 实例
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
    }
    public function onTask(\swoole_http_server $server, int $taskId, int $fromWorkerId,$data)
    {
        if($data instanceof SuperClosure){
            try{
                return $data($server,$taskId,$fromWorkerId);
            }catch (\Throwable $throwable){
                dump($throwable);
            }
        }
    }
    public function onFinish(\swoole_http_server $serv, int $task_id, string $data)
    {
        dump($data);
    }

    /**
     * 请求到达事件
     * @param \swoole_http_request $request_raw
     * @param \swoole_http_response $response_raw
     * @param \swoole_http_server $server
     */
    public function onRequest(\swoole_http_request $request_raw, \swoole_http_response $response_raw,\swoole_http_server $server)
    {
        try{
            /**
             * 获取全局Server 实例
             */
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
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
        /**
         * 获取全局Server 实例
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
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
        /**
         * 获取全局Server 实例
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
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
        /**
         * 获取全局Server 实例
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
        echo "WebServerOpOpened\n";
    }
}