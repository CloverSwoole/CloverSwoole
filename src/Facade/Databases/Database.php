<?php
namespace CloverSwoole\CloverSwoole\Facade\Databases;
use CloverSwoole\CloverSwoole\Framework;
use Illuminate\Database\Capsule\Manager;

/**
 * 数据库服务
 * Class Database
 * @package CloverSwoole\CloverSwoole\Facade\Databases
 */
class Database implements DatabaseInterface
{
    /**
     * 启动服务
     */
    public function boot()
    {
        if(!Framework::exists_bind(\CloverSwoole\CloverSwoole\Facade\Databases\ConfigInterface::class)){
            Framework::getContainerInterface() -> bind(\CloverSwoole\CloverSwoole\Facade\Databases\ConfigInterface::class,Config::class);
        }
        /**
         * 获取配置
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Databases\ConfigInterface::class) -> boot();
        /**
         * 实例化组件
         */
        $Manager = new Manager(\CloverSwoole\CloverSwoole\Framework::getContainerInterface());
        /**
         * 注入配置
         */
        $Manager -> addConnection(\CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['database']);
        /**
         * 设置全局访问
         */
        $Manager -> setAsGlobal();
        /**
         * 启动组件
         */
        $Manager -> bootEloquent();
    }
}