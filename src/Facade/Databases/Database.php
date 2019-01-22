<?php
namespace CloverSwoole\CloverSwoole\Facade\Databases;
use Illuminate\Container\Container;
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
        $container -> make(\CloverSwoole\CloverSwoole\Facade\Databases\ConfigInterface::class) -> boot($container);
        /**
         * 实例化组件
         */
        $Manager = new Manager($container);
        /**
         * 注入配置
         */
        $Manager -> addConnection($container['config']['database']);
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