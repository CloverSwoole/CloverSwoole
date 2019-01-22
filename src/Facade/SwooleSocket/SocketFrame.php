<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
/**
 * Class SocketFrame
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
class SocketFrame
{
    /**
     * 静态全局实例
     * @var null | SocketFrame
     */
    protected static $interface = null;
    /**
     * 是否开启全局访问
     * @var null | bool
     */
    protected static $global_response = null;
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
     * @param \swoole_websocket_frame $frame
     */
    public function boot(\swoole_websocket_frame $frame)
    {
        $this -> frame = $frame;
        return $this;
    }
}