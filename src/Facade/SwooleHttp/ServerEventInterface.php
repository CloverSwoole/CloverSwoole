<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
/**
 * Swoole Http Event Model
 * Interface ServerEventInterface
 * @package Itxiao6\Framework\Facade\SwooleHttp
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
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     * @return mixed
     */
    public function onRequest(\swoole_http_request $request, \swoole_http_response $response);

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
     * 服务打开
     * @param \Swoole\Http\Server $server
     * @param \Swoole\Http\Server $request
     * @return mixed
     */
    public function onOpen(\Swoole\Http\Server $server, \Swoole\Http\Server $request);
}