<?php
namespace Itxiao6\Framework;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Databases\Database;
use Itxiao6\Framework\Facade\Databases\DatabaseInterface;

/**
 * Class Kernel
 * @package Itxiao6\Framework
 */
class Kernel
{
    /**
     * 初始化框架
     * @param Container|null $container
     * @return Container|null
     */
    public static function getInterface(?Container $container = null)
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
        $container -> bind(DatabaseInterface::class,function($container){ return Database::boot($container); });
        /**
         * 设置别名
         */
        $container -> alias(DatabaseInterface::class,'db');
        /**
         * 注入框架入口
         */
        $container -> bind(Framework::class);
        /**
         * 设置别名
         */
        $container -> alias(Framework::class,'framework');
        /**
         * 返回容器
         */
        return $container;
    }
}