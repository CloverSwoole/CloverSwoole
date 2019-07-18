<?php
namespace CloverSwoole\CloverSwoole\Facade\Swoole\SwooleSocket;
/**
 * Class SocketFrame
 * @package CloverSwoole\CloverSwoole\Facade\Swoole\SwooleSocket
 */
class SocketFrame
{
    /**
     * 静态全局实例
     * @var null | SocketFrame
     */
    protected static $interface = null;
    /**
     * @var null | \swoole_websocket_frame
     */
    protected $frame = null;
    /**
     * 获取接口
     * @return SocketFrame|null
     */
    public static function getInterface()
    {
        return self::$interface;
    }
    /**
     * 获取opcode
     * @return mixed
     */
    public function getOpcode()
    {
        return $this -> frame -> opcode;
    }

    /**
     * 获取传输状态
     * @return mixed
     */
    public function getFinish()
    {
        return $this -> frame -> finish;
    }

    /**
     * 获取数据
     * @return mixed
     */
    public function getData()
    {
        return $this -> frame -> data;
    }

    /**
     * 获取连接句柄
     * @return mixed
     */
    public function getFd()
    {
        return $this -> frame -> fd;
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
     * @param \swoole_websocket_frame $frame
     */
    public function boot(\swoole_websocket_frame $frame)
    {
        $this -> frame = $frame;
        return $this;
    }
}