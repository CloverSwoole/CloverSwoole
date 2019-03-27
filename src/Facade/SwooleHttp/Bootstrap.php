<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Framework;
use Illuminate\Support\Fluent;

/**
 * Swoole Http 组件启动器
 * Class Bootstrap
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class Bootstrap
{
    /**
     * 启动
     * @param $config
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(&$config)
    {
        /**
         * 初始化配置
         */
        if(!(isset(Framework::getContainerInterface()['config']) && Framework::getContainerInterface()['config'] instanceof Fluent)){
            Framework::getContainerInterface() -> instance('config',new Fluent());
        }
        /**
         * 默认配置
         */
        if(!Framework::exists_bind(ConfigInterface::class)){
            Framework::getContainerInterface() -> bind(ConfigInterface::class,Config::class);
        }
        /**
         * 初始化组件配置
         */
        Framework::getContainerInterface() -> make(ConfigInterface::class) -> boot();
        /**
         * 获取swoole_http 配置
         */
        $config = \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http'];
    }
}