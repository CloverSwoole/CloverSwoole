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
     * @param \Swoole\Websocket\Server $server
     * @return mixed
     */
    public function onShutdown(\Swoole\Websocket\Server $server);

    /**
     * 服务启动
     * @param \Swoole\Websocket\Server $server
     * @return mixed
     */
    public function onStart(\Swoole\Websocket\Server $server);

    /**
     * 连接到达
     * @param \Swoole\Websocket\Server $server
     * @param \Swoole\Http\Request $request
     * @return mixed
     */
    public function onOpen(\Swoole\Websocket\Server $server, \Swoole\Http\Request $request);

    /**
     * 请求到达
     * @param \Swoole\Http\Request $request_raw
     * @param \Swoole\Http\Response $response_raw
     * @param \Swoole\Http\Server $server
     * @return mixed
     */
    public function onRequest(\Swoole\Http\Request $request_raw, \Swoole\Http\Response $response_raw,\Swoole\Http\Server $server);

    /**
     * 消息到达
     * @param \Swoole\Websocket\Server $server
     * @param \Swoole\Websocket\Frame $frame
     * @return mixed
     */
    public function onMessage(\Swoole\Websocket\Server $server, \Swoole\Websocket\Frame $frame);

    /**
     * @param \Swoole\Websocket\Server $serv
     * @param int $task_id
     * @param string $data
     * @return mixed
     */
    public function onFinish(\Swoole\Websocket\Server $serv, int $task_id, string $data);

    /**
     * @param \Swoole\WebSocket\Server $server
     * @param int $taskId
     * @param int $fromWorkerId
     * @param $data
     * @return mixed
     */
    public function onTask(\Swoole\WebSocket\Server $server, int $taskId, int $fromWorkerId,$data);
    /**
     * 连接关闭
     * @param \Swoole\WebSocket\Server $server
     * @param $fd
     * @return mixed
     */
    public function onClose(\Swoole\WebSocket\Server $server,$fd);
}