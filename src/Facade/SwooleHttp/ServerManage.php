<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SocketServer;

/**
 * Server
 * Class ServerManage
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class ServerManage extends ServerManageInterface
{
    /**
     * 静态全局实例
     * @var null | ServerManage
     */
    protected static $interface = null;
    /**
     * @var null | \swoole_websocket_server
     */
    protected $server = null;
    /**
     * 获取接口
     * @return static|null
     */
    public static function getInterface()
    {
        return self::$interface;
    }
    /**
     * 设置全局访问
     * @param $bool
     */
    public function setAsGlobal($bool = true)
    {
        if($bool){
            static::$interface = $this;
        }else{
            static::$interface = null;
        }
    }
    /**
     * Socket Server 实例
     * @param \swoole_websocket_server $server
     */
    public function boot($server)
    {
        $this -> server = $server;
        return $this;
    }

    /**
     * 推送信息
     * @return mixed
     */
    public function push()
    {
        return $this -> getRawServer() -> push(...func_get_args());
    }

    /**
     * 获取原生Server
     * @return null|\swoole_websocket_server
     */
    public function getRawServer()
    {
        return $this -> server;
    }
}