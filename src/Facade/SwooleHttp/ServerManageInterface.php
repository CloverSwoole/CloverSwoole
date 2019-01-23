<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SocketServer;

/**
 * Server
 * Class ServerManage
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
abstract class ServerManageInterface
{
    /**
     * 获取接口
     * @return SocketServer|null
     */
    abstract public static function getInterface();
    /**
     * 设置全局访问
     * @param $bool
     */
    abstract public function setAsGlobal($bool = true);
    /**
     * Socket Server 实例
     * @param mixed $server
     */
    abstract public function boot($server);

    /**
     * 获取原生Server
     * @return null|mixed
     */
    abstract public function getRawServer();
}