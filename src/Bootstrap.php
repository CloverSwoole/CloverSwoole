<?php
namespace CloverSwoole\CloverSwoole;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManage;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManageInterface;
use CloverSwoole\CloverSwoole\Facade\Databases\Database;
use CloverSwoole\CloverSwoole\Facade\Databases\DatabaseInterface;
use CloverSwoole\CloverSwoole\Facade\Environment\Environment;
use CloverSwoole\CloverSwoole\Facade\Environment\EnvironmentInsterface;
use CloverSwoole\CloverSwoole\Facade\Route\Config;
use CloverSwoole\CloverSwoole\Facade\Route\ConfigInterface;
use CloverSwoole\CloverSwoole\Facade\Route\Dynamic;
use CloverSwoole\CloverSwoole\Facade\Route\DynamicInterface;
use CloverSwoole\CloverSwoole\Facade\Route\Route;
use CloverSwoole\CloverSwoole\Facade\Route\RouteInterface;
use CloverSwoole\CloverSwoole\Facade\Route\StaticInterface;
use CloverSwoole\CloverSwoole\Facade\Route\Statics;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\HttpServer;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\HttpServerInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\Request;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\Response;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\SwooleHttpInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SwooleSocket;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SwooleSocketInterface;
use CloverSwoole\CloverSwoole\Facade\Whoops\Whoops;
use CloverSwoole\CloverSwoole\Facade\Whoops\WhoopsInterface;

/**
 * 框架默认启动器
 * Class Bootstrap
 * @package CloverSwoole\CloverSwoole
 */
class Bootstrap
{
    public function __construct()
    {
    }

    /**
     * 初始化框架
     */
    public function init()
    {
        /**
         * 初始化配置
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> instance('config',new \Illuminate\Support\Fluent());
        /**
         * 数据库组件
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(DatabaseInterface::class,Database::class);
        /**
         * 设置别名
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> alias(DatabaseInterface::class,'db');
        /**
         * 绑定默认的环境自适应
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(EnvironmentInsterface::class,Environment::class);
        /**
         * 环境自适应
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> alias(EnvironmentInsterface::class,'environment');
        /**
         * swoole http
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(SwooleHttpInterface::class,SwooleHttp::class);
        /**
         * 设置别名
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> alias(SwooleHttpInterface::class,'swoole_http');
        /**
         * swoole socket
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(SwooleSocketInterface::class,SwooleSocket::class);
        /**
         * 设置别名
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> alias(SwooleSocketInterface::class,'swoole_socket');
        /**
         * 注入框架入口
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(Framework::class);
        /**
         * 设置别名
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> alias(Framework::class,'framework');
        /**
         * 注入路由配置
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(ConfigInterface::class,Config::class);
        /**
         * HttpServer 启动器注入
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(HttpServerInterface::class,HttpServer::class);
        /**
         * 注入静态路由处理方法
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(StaticInterface::class,Statics::class);
        /**
         * 注入动态路由处理方法
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(DynamicInterface::class,Dynamic::class);
        /**
         * 注入请求依赖
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\Http\Request::class,Request::class);
        /**
         * 注入响应依赖
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\Http\Response::class,Response::class);
        /**
         * 注入路由组件
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(RouteInterface::class,Route::class);
        /**
         * 注入异常处理组件
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(WhoopsInterface::class,Whoops::class);
        /**
         * Server 管理
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(ServerManageInterface::class,ServerManage::class);
    }
}