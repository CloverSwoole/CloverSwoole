<?php

namespace CloverSwoole\FastCgiServer;

use CloverSwoole\Exception\ExceptionHandler;

/**
 * Class Command
 * @package CloverSwoole\FastCgiServer
 */
class Command
{
    /**
     * 获取实例
     * @return Command
     */
    public static function getInterface()
    {
        return (new static(...func_get_args()));
    }

    /**
     * 运行应用
     */
    public function run()
    {
        /**
         * 放置全局请求实例
         */
        (new Request())->setAsGlobal();
        /**
         * 放置全局响应实例
         */
        (new Response())->setAsGlobal();
        try {
            /**
             * 路由初始化
             */
            \CloverSwoole\Route\Route::getInterface();
        } catch (\Throwable $throwable) {
            /**
             * 捕获路由异常
             */
            ExceptionHandler::getInterface()->catchRoute($throwable);
        }
    }
}