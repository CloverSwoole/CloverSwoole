<?php

namespace CloverSwoole;

use CloverSwoole\FastCgiServer\Command as FastCgiServerCommand;
use CloverSwoole\Swoole\SwooleHttp\Command as SwooleHttpCommand;
use CloverSwoole\Swoole\SwooleSocket\Command as SwooleScoketCommand;
use CloverSwoole\Utility\Command;

/**
 * 框架主入口
 * Class CloverSwoole
 * @package CloverSwoole
 */
class CloverSwoole
{
    /**
     * 运行Swoole Http 服务
     */
    public static function runSwooleHttpServer()
    {
        /**
         * 运行命令
         */
        SwooleHttpCommand::getInterface()->run(Command::commandParser());
    }

    /**
     * 运行Swoole Scoket 服务
     */
    public static function runSwooleSocketServer()
    {
        /**
         * 运行命令
         */
        SwooleScoketCommand::getInterface()->run(Command::commandParser());
    }

    /**
     * 运行传统模式的服务
     */
    public static function runFastCgiServer()
    {
        /**
         * 运行命令
         */
        FastCgiServerCommand::getInterface()->run();
    }
}