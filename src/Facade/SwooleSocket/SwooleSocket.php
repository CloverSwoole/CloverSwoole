<?php
namespace Itxiao6\Framework\Facade\SwooleSocket;
use Illuminate\Container\Container;

/**
 * SWOOLE Socket 组件
 * Class SwooleSocket
 * @package Itxiao6\Framework\Facade\SwooleSocket
 */
class SwooleSocket
{
    /**
     * 启动服务
     * @param Container|null $container
     */
    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        /**
         * 获取配置
         */
        $container -> make(Config::class) -> boot($container);
        /**
         * 获取server
         */
        $http = new \Swoole\WebSocket\Server($container['config']['swoole_socket']['host'], $container['config']['swoole_socket']['port']);
        /**
         * 判断参数是否存在
         */
        if(is_array($container['config']['swoole_socket']['server']) && count($container['config']['swoole_socket']['server']) > 0){
            /**
             * 设置参数
             */
            $http->set($container['config']['swoole_socket']['server']);
        }
        /**
         * 监听启动事件
         */
        $http->on("start", function ($server) use($container){
            echo "Swoole http and socket server is started at http://127.0.0.1:{$container['config']['swoole_socket']['port']} and ws://127.0.0.1:{$container['config']['swoole_socket']['port']}\n";
        });
        /**
         * 监听请求到达 事件
         */
        $http->on("request", function (){
            echo "请求到达\n";
        });
        /**
         * 监听连接到达 事件
         */
        $http->on("open", function (){
            echo "连接到达\n";
        });
        /**
         * 监听消息到达 事件
         */
        $http->on("message", function (){
            echo "消息到达\n";
        });
        /**
         * 监听关闭 事件
         */
        $http->on("close", function (){
            echo "连接关闭\n";
        });
        /**
         * 启动服务
         */
        $http->start();
    }
    /**
     * 重启服务
     * @param Container|null $container
     */
    public function restart(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
    }
    /**
     * 重载服务
     * @param Container|null $container
     */
    public function reload(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        echo "Socket and Server Reloaded at ".date('Y-m-d H:i:s')."\n";
        \Swoole\Process::kill(8103,SIGUSR1);
    }
}