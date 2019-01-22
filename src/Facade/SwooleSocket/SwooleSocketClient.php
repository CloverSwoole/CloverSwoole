<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;

use CloverSwoole\CloverSwoole\Facade\Socket\SocketClient;

/**
 * Class SwooleSocketClient
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
class SwooleSocketClient extends SocketClient
{
    /**
     * 消息类型
     * @var int|mixed
     */
    protected $opcode = 1;
    /**
     * 连接句柄标识
     * @var string
     */
    protected $fd = '';
    /**
     * 客户端消息信息
     * @var string | mixed
     */
    protected $data = null;
    /**
     * 是否完成
     * @var bool
     */
    protected $finish = true;
    /**
     * @var null | \swoole_websocket_server
     */
    protected $server = null;
    /**
     * Swoole 启动
     * @param \swoole_websocket_server $server
     * @param \swoole_websocket_frame $frame
     * @return $this
     */
    public function bootSwoole(\swoole_websocket_server $server, \swoole_websocket_frame $frame)
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