<?php
namespace CloverSwoole\Swoole\SwooleHttp;
use CloverSwoole\Swoole\Abstrats\ServerCommand;
use Swoole\Http\Server;

/**
 * Class Command
 * @package CloverSwoole\Swoole\SwooleHttp
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
        $this->setName('swoole_http_server');
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
    }
}