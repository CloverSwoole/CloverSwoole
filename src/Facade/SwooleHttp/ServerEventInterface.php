<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
/**
 * Swoole Http Event Model
 * Interface ServerEventInterface
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
interface ServerEventInterface
{
    /**
     * 服务启动
     * @param \Swoole\Http\Server $server
     * @return mixed
     */
    public function onStart(\Swoole\Http\Server $server);

    /**
     * 请求到达
     * @param \Swoole\Http\Request $request
     * @param \Swoole\Http\Response $response
     * @param \Swoole\Http\Server $server
     * @return mixed
     */
    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response,\Swoole\Http\Server $server);

    /**
     * 服务关闭
     * @param \Swoole\Http\Server $server
     * @return mixed
     */
    public function onShutdown(\Swoole\Http\Server $server);

    /**
     * 连接关闭
     * @param \Swoole\Http\Server $server
     * @param $fd
     * @return mixed
     */
    public function onClose(\Swoole\Http\Server $server,$fd);

    /**
     * 处理任务
     * @param \Swoole\Http\Server $server
     * @param int $taskId
     * @param int $fromWorkerId
     * @param $data
     * @return mixed
     */
    public function onTask(\Swoole\Http\Server $server, int $taskId, int $fromWorkerId,$data);

    /**
     * 任务完成
     * @param \Swoole\Http\Server $serv
     * @param int $task_id
     * @param string $data
     * @return mixed
     */
    public function onFinish(\Swoole\Http\Server $serv, int $task_id, string $data);

    /**
     * 打开连接
     * @param \Swoole\Http\Server $server
     * @param \Swoole\Http\Server $request
     * @return mixed
     */
    public function onOpen(\Swoole\Http\Server $server, \Swoole\Http\Server $request);
}