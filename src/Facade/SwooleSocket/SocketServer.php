<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
/**
 * Class SocketServer
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
class SocketServer
{
    /**
     * 静态全局实例
     * @var null | SocketClient
     */
    protected static $interface = null;
    /**
     * 是否开启全局访问
     * @var null | bool
     */
    protected static $global_response = null;
    /**
     * @var null | \swoole_websocket_server
     */
    protected $server = null;
    /**
     * 获取接口
     * @return SocketClient|null
     */
    protected static function getInterface()
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
            static::$global_response = $this;
        }else{
            static::$global_response = null;
        }
    }
    /**
     * Socket Server 实例
     * @param \swoole_websocket_server $server
     */
    public function boot(\swoole_websocket_server $server)
    {
        $this -> server = $server;
        return $this;
    }
}