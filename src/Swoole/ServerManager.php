<?php
namespace CloverSwoole\Swoole;
use Swoole\Websocket\Server;

/**
 * Server 管理器
 * Class ServerManager
 * @package CloverSwoole\CloverSwoole\Facade\Swoole
 */
class ServerManager
{
    /**
     * 静态全局实例
     * @var null | ServerManager
     */
    protected static $interface = null;
    /**
     * @var null | Server
     */
    protected $server = null;
    public function __construct($server)
    {
        $this -> server = $server;
    }

    /**
     * 获取接口
     * @param \Swoole\Server $server
     * @return ServerManager|null
     */
    public static function getInterface(?\Swoole\Server $server = null)
    {
        if(self::$interface == null){
            return new static(...func_get_args());
        }
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
     * 获取SwooleServer
     * @return null|Server
     */
    public function getSwooleRawServer()
    {
        return $this -> server;
    }
}