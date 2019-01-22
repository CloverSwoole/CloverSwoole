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
     * @var null | \swoole_websocket_server
     */
    protected $server = null;
    /**
     * 获取接口
     * @return SocketServer|null
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
    public function boot(\swoole_websocket_server $server)
    {
        $this -> server = $server;
        return $this;
    }
    /**
     * 推送数据
     * @param int $fd
     * @param mixed|string $data
     * @param int $opcode
     * @param bool $finish
     */
    public function push(int $fd, $data,int $opcode = 1, bool $finish = true)
    {
        $this -> server -> push(...func_get_args());
    }
    /**
     * 客户端是否存在
     * @param int $fd
     * @return bool
     */
    public function exist(int $fd)
    {
        return $this -> server -> exist($fd);
    }
}