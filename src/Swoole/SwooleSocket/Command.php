<?php
namespace CloverSwoole\Swoole\SwooleSocket;
use CloverSwoole\Swoole\Abstrats\ServerCommand;
use CloverSwoole\Swoole\ServerManager;
use Swoole\WebSocket\Server;

/**
 * Class Command
 * @package CloverSwoole\Swoole\SwooleSocket
 */
class Command extends ServerCommand
{
    /**
     * 重写配置项
     */
    public function configure()
    {
        /**
         * 设置命令
         */
        $this->setName('swoole_socket_server');
    }

    /**
     * 重新创建Server
     */
    protected function create_server()
    {
        /**
         * 实例化server
         */
        $this->server = new Server($this->config['host'], $this->config['port']);
        /**
         * 放置全局Server
         */
        ServerManager::getInterface($this->server) ->setAsGlobal();
    }
}