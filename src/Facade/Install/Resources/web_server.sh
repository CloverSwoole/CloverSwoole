#!/usr/bin/env php
<?php
include __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
use CloverSwoole\CloverSwoole\Bootstrap;

/**
 * 获取框架实例
 */
//$app = \CloverSwoole\CloverSwoole\Framework::getContainerInterface();
/**
 * 绑定启动器
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\BootstrapInterface::class,Bootstrap::class);
/**
 * 初始化框架
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\BootstrapInterface::class) -> init();

/**
 * <=================== 自定义操作 ===========================>
 */
/**
 * 加载环境配置
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make('environment') -> boot(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
/**
 * 注入数据库配置(自定义配置)
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\Databases\ConfigInterface::class,\Config\Databases::class);
/**
 * 注入swoole HTTP 组件配置(自定义配置)
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\SwooleHttp\ConfigInterface::class,\Config\SwooleHttpConfig::class);
/**
 * Swoole Socket 配置注入(自定义配置)
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\SwooleSocket\ConfigInterface::class,\Config\SwooleSocketConfig::class);
/**
 * Swoole Socket 定义事件模型
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\SwooleSocket\ServerEventInterface::class,\App\Event\SwooleSocketEvent::class);
/**
 * Swoole Http 自定义事件模型
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerEventInterface::class,\App\Event\SwooleHttpEvent::class);

/**
 * <=================== 启动组件 =====================>
 */

/**
 * 启动swoole http
 */
\CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make('swoole_http') -> boot();
