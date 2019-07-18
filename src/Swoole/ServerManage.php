<?php
namespace CloverSwoole\CloverSwoole\Facade\Swoole;
use Swoole\Websocket\Server;

/**
 * Server 管理器
 * Class ServerManage
 * @package CloverSwoole\CloverSwoole\Facade\Swoole
 */
class ServerManage
{
    /**
     * 静态全局实例
     * @var null | ServerManage
     */
    protected static $interface = null;
    /**
     * @var null | Server
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
     * 获取SwooleServer
     * @return null|Server
     */
    public function getSwooleRawServer()
    {
        return $this -> server;
    }
}