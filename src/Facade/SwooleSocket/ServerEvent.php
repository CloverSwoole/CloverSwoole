<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
use CloverSwoole\CloverSwoole\Facade\Route\RouteInterface;
use CloverSwoole\CloverSwoole\Facade\SuperClosure\SuperClosure;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManage;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManageInterface;
use CloverSwoole\CloverSwoole\Framework;

/**
 * Server 事件模型
 * Class ServerEvent
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
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
     * 服务关闭
     * @param \Swoole\Websocket\Server $server
     * @param $fd
     * @return mixed|void
     */
    public function onClose(\Swoole\Websocket\Server $server,$fd)
    {
        echo "Server Closed\n";
    }

    /**
     * 服务关闭
     * @param \Swoole\Websocket\Server $server
     * @return mixed|void
     */
    public function onShutdown(\Swoole\Websocket\Server $server)
    {
        echo "WebServerOnShutdownEd\n";
    }

    /**
     * 服务关闭
     * @param \Swoole\Websocket\Server $server
     * @param \Swoole\Http\Request $request
     * @return mixed|void
     */
    public function onOpen(\Swoole\Websocket\Server $server, \Swoole\Http\Request $request)
    {
        echo "opOpened\n";
    }

    /**
     * 异步投递
     * @param \Swoole\WebSocket\Server $server
     * @param int $taskId
     * @param int $fromWorkerId
     * @param $data
     * @return mixed|null
     */
    public function onTask(\Swoole\WebSocket\Server $server, int $taskId, int $fromWorkerId,$data)
    {
        if($data instanceof SuperClosure){
            try{
                return $data($server,$taskId,$fromWorkerId);
            }catch (\Throwable $throwable){
                dump($throwable);
            }
        }
    }

    /**
     * 任务完成
     * @param \Swoole\Websocket\Server $serv
     * @param int $task_id
     * @param string $data
     */
    public function onFinish(\Swoole\Websocket\Server $serv, int $task_id, string $data)
    {
        dump($data);
    }

    /**
     * 消息到达
     * @param \Swoole\Websocket\Server $server
     * @param \Swoole\Websocket\Frame $frame
     * @return mixed|void
     */
    public function onMessage(\Swoole\Websocket\Server $server, \Swoole\Websocket\Frame $frame)
    {
        echo "onMessageEd\n";
    }

    /**
     * 当请求到达
     * @param \Swoole\Http\Request $request_raw
     * @param \Swoole\Http\Response $response_raw
     * @param \Swoole\Http\Server $server
     * @return mixed|void
     */
    public function onRequest(\Swoole\Http\Request $request_raw, \Swoole\Http\Response $response_raw,\Swoole\Http\Server $server)
    {
        try{
            /**
             * 判断是否预置了 ServerManage
             */
            if(!Framework::exists_bind(ServerManageInterface::class)){
                Framework::getContainerInterface() -> bind(ServerManageInterface::class,ServerManage::class);
            }
            /**
             * 获取全局Server 实例
             */
            Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
            /**
             * 判断是否预置了 Request 主控类
             */
            if(!Framework::exists_bind(\CloverSwoole\CloverSwoole\Facade\Http\Request::class)){
                Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\Http\Request::class,Request::class);
            }
            /**
             * 实例化 Request
             */
            Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Request::class) -> boot($request_raw) -> setAsGlobal();
            /**
             * 判断是否预置了 Response 主控类
             */
            if(!Framework::exists_bind(\CloverSwoole\CloverSwoole\Facade\Http\Response::class)){
                Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\Http\Response::class,Response::class);
            }
            /**
             * 实例化 Response
             */
            Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Response::class) -> boot($response_raw) -> setAsGlobal();
            /**
             * 判断路由是否已经预置
             */
            if(!Framework::exists_bind(RouteInterface::class)){
                Framework::getContainerInterface()->bind(RouteInterface::class,\CloverSwoole\CloverSwoole\Facade\Route\Route::class);
            }
            /**
             * 启动路由组件
             */
            Framework::getContainerInterface() -> make(RouteInterface::class) -> boot();
            /**
             * 如果没有结束响应则 后置结束
             */
            if(!Response::getInterface()->ResponseIsEnd()){
                Response::getInterface()->endResponse();
            }
        }catch (\Throwable $exception){
            dump($exception);
            /**
             * 处理异常 TODO
             */
        }
    }

    /**
     * 服务启动
     * @param \Swoole\Websocket\Server $server
     * @return mixed|void
     */
    public function onStart(\Swoole\Websocket\Server $server)
    {
        echo "onStarted\n";
    }
}