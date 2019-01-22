<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
/**
 * Server 事件模型
 * Class ServerEvent
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
class ServerEvent implements ServerEventInterface
{
    /**
     * 服务关闭
     * @param $server
     * @param int $fd
     * @return mixed|void
     */
    public function onClose($server,$fd)
    {
        echo "Server Closed\n";
    }

    /**
     * 服务关闭
     * @param \swoole_websocket_server $server
     * @param \swoole_http_request $request
     * @return mixed|void
     */
    public function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        echo "opOpened\n";
    }

    /**
     * 消息到达
     * @param \swoole_websocket_server $server
     * @param \swoole_websocket_frame $frame
     * @return mixed|void
     */
    public function onMessage(\swoole_websocket_server $server, \swoole_websocket_frame $frame)
    {
        echo "onMessageEd\n";
    }

    /**
     * 消息到达
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     * @return mixed|void
     */
    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        echo "onRequestEd\n";
    }

    /**
     * 服务关闭
     * @param \swoole_websocket_server $server
     * @return mixed|void
     */
    public function onShutdown(\swoole_websocket_server $server)
    {
        echo "onShutdownEd\n";
    }

    /**
     * 服务启动
     * @param \swoole_websocket_server $server
     * @return mixed|void
     */
    public function onStart(\swoole_websocket_server $server)
    {
        echo "onStarted\n";
    }
}