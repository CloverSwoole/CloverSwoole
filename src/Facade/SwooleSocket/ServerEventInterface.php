<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
/**
 * 服务事件接口
 * Interface ServerEventInterface
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
interface ServerEventInterface
{
    /**
     * 当服务关闭时
     * @param \swoole_websocket_server $server
     * @return mixed
     */
    public function onShutdown(\swoole_websocket_server $server);
    /**
     * 服务启动
     * @param \swoole_websocket_server $server
     * @return mixed
     */
    public function onStart(\swoole_websocket_server $server);
    /**
     * 连接到达
     * @param \swoole_websocket_server $server
     * @param \swoole_http_request $request
     * @return mixed
     */
    public function onOpen(\swoole_websocket_server $server, \swoole_http_request $request);

    /**
     * 请求到达
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     * @return mixed
     */
    public function onRequest(\swoole_http_request $request,\swoole_http_response $response);

    /**
     * 消息到达
     * @param \swoole_websocket_server $server
     * @param \swoole_websocket_frame $frame
     * @return mixed
     */
    public function onMessage(\swoole_websocket_server  $server, \swoole_websocket_frame $frame);

    /**
     * 连接关闭
     * @param $server
     * @param $fd
     * @return mixed
     */
    public function onClose($server,$fd);
}