<?php
namespace Itxiao6\Framework;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Databases\Database;
use Itxiao6\Framework\Facade\Databases\DatabaseInterface;
use Itxiao6\Framework\Facade\Environment\Environment;
use Itxiao6\Framework\Facade\Environment\EnvironmentInsterface;
use Itxiao6\Framework\Facade\Route\Config;
use Itxiao6\Framework\Facade\Route\ConfigInterface;
use Itxiao6\Framework\Facade\Route\Dynamic;
use Itxiao6\Framework\Facade\Route\DynamicInterface;
use Itxiao6\Framework\Facade\Route\StaticInterface;
use Itxiao6\Framework\Facade\Route\Statics;
use Itxiao6\Framework\Facade\SwooleHttp\HttpServer;
use Itxiao6\Framework\Facade\SwooleHttp\HttpServerInterface;
use Itxiao6\Framework\Facade\SwooleHttp\SwooleHttp;
use Itxiao6\Framework\Facade\SwooleHttp\SwooleHttpInterface;
use Itxiao6\Framework\Facade\SwooleSocket\SwooleSocket;
use Itxiao6\Framework\Facade\SwooleSocket\SwooleSocketInterface;

/**
 * 框架默认启动器
 * Class Bootstrap
 * @package Itxiao6\Framework
 */
class Bootstrap
{
    public function __construct()
    {
    }

    /**
     * 初始化框架
     * @param Container|null $container
     * @return Container|null
     */
    public function init(?Container $container = null)
    {
        /**
         * 判断是否传入了服务容器
         */
        if(!($container instanceof Container)){
            $container = new Container();
        }
        /**
         * 初始化配置
         */
        $container -> instance('config',new \Illuminate\Support\Fluent());
        /**
         * 数据库组件
         */
        $container -> bind(DatabaseInterface::class,Database::class);
        /**
         * 设置别名
         */
        $container -> alias(DatabaseInterface::class,'db');
        /**
         * 绑定默认的环境自适应
         */
        $container -> bind(EnvironmentInsterface::class,Environment::class);
        /**
         * 环境自适应
         */
        $container -> alias(EnvironmentInsterface::class,'environment');
        /**
         * swoole http
         */
        $container -> bind(SwooleHttpInterface::class,SwooleHttp::class);
        /**
         * 设置别名
         */
        $container -> alias(SwooleHttpInterface::class,'swoole_http');
        /**
         * swoole socket
         */
        $container -> bind(SwooleSocketInterface::class,SwooleSocket::class);
        /**
         * 设置别名
         */
        $container -> alias(SwooleSocketInterface::class,'swoole_socket');
        /**
         * 注入框架入口
         */
        $container -> bind(Framework::class);
        /**
         * 设置别名
         */
        $container -> alias(Framework::class,'framework');
        /**
         * 注入路由配置
         */
        $container -> bind(ConfigInterface::class,Config::class);
        /**
         * HttpServer 启动器注入
         */
        $container -> bind(HttpServerInterface::class,HttpServer::class);
        /**
         * 注入静态路由处理方法
         */
        $container -> bind(StaticInterface::class,Statics::class);
        /**
         * 注入动态路由处理方法
         */
        $container -> bind(DynamicInterface::class,Dynamic::class);
        /**
         * 返回容器
         */
        return $container;
    }
}