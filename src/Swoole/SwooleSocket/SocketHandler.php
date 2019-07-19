<?php
namespace CloverSwoole\Swoole\SwooleSocket;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\WebSocket\Frame;

/**
 * Scoket 处理程序
 * Class SocketHandler
 * @package CloverSwoole\Swolle\SwooleSocket
 */
class SocketHandler
{
    /**
     * 自定义处理程序
     * @var array
     */
    protected static $handler = [
        'onMessage' => null,
        'onOpen' => null,
        'onClose' => null,
    ];

    /**
     * 设置onMessage处理程序
     * @param \Closure $callback
     */
    public static function setOnMessageHandler(\Closure $callback)
    {
        self::$handler['onMessage'] = $callback;
    }

    /**
     * 设置onOpen处理程序
     * @param \Closure $callback
     */
    public static function setOnOpenHandler(\Closure $callback)
    {
        self::$handler['onOpen'] = $callback;
    }

    /**
     * 设置onClose处理程序
     * @param \Closure $callback
     */
    public static function setOnCloseHandler(\Closure $callback)
    {
        self::$handler['onClose'] = $callback;
    }

    /**
     * 消息到达
     * @param Server $server
     * @param Frame $frame
     * @return mixed
     */
    public static function onMessage(Server $server, Frame $frame)
    {
        /**
         * 判断是否设置了自定义处理程序
         */
        if (self::$handler['onMessage'] instanceof \Closure) {
            $closure = self::$handler['onMessage'];
            return $closure(...func_get_args());
        }
        echo "Socket连接:{$frame -> fd},消息到达:{$frame -> data}\n";
    }

    /**
     * 建立连接
     * @param Server $server
     * @param Request $request
     * @return mixed
     */
    public static function onOpen(Server $server, Request $request)
    {
        /**
         * 判断是否设置了自定义处理程序
         */
        if (self::$handler['onOpen'] instanceof \Closure) {
            $closure = self::$handler['onOpen'];
            return $closure(...func_get_args());
        }
        echo "Socket连接:{$request -> fd} 建立成功\n";
    }

    /**
     * 连接关闭
     * @param Server $server
     * @param int $fd
     * @return mixed
     */
    public static function onClose(Server $server, int $fd)
    {
        /**
         * 判断是否设置了自定义处理程序
         */
        if (self::$handler['onClose'] instanceof \Closure) {
            $closure = self::$handler['onClose'];
            return $closure(...func_get_args());
        }
        echo "Socket断开连接:{$fd}\n";
    }
}