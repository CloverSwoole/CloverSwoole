<?php
namespace CloverSwoole\CloverSwoole\Facade\Socket;

/**
 * Class SocketClient
 * @package CloverSwoole\CloverSwoole\Facade\Socket
 */
abstract class SocketClient
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
     * 推送数据
     * @param int $fd
     * @param string|mixed $data
     * @param int $opcode
     * @param bool $finish
     */
    abstract function push(int $fd,$data);

    /**
     * 客户端是否存在
     * @param int $fd
     * @return bool
     */
    abstract public function exist(int $fd);
}