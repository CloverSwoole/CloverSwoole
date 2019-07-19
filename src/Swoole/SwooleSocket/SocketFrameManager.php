<?php
namespace CloverSwoole\Swoole\SwooleSocket;
use Swoole\WebSocket\Frame;

/**
 * Frame管理器
 * Class SocketFrameManager
 * @package CloverSwoole\Swoole\SwooleSocket
 */
class SocketFrameManager
{
    /**
     * 静态全局实例
     * @var null | SocketFrameManager
     */
    protected static $interface = null;
    /**
     * @var null | \swoole_websocket_frame
     */
    protected $frame = null;
    /**
     * 获取接口
     * @return SocketFrameManager|null
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
     * 构造器
     * SocketFrameManager constructor.
     * @param Frame $frame
     */
    public function __construct(Frame $frame)
    {
        $this -> frame = $frame;
    }
}