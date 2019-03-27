<?php
namespace CloverSwoole\CloverSwoole;
use Illuminate\Container\Container;

/**
 * 框架入口
 * Class Framework
 * @package CloverSwoole\CloverSwoole
 */
class Framework
{
    /**
     * @var null|array|Container
     */
    protected static $container = null;

    /**
     * 获取容器
     * @param string $name
     * @return mixed|Container
     */
    public static function getContainerInterface($name = '')
    {
        if(!(self::$container[$name] instanceof Container)){
            self::$container[$name] = new Container;
        }
        return self::$container[$name];
    }

    /**
     * @param $interface
     * @param string $container_name
     * @return bool
     */
    public static function exists_bind($interface,$container_name = '')
    {
        return (isset(static::getContainerInterface($container_name) -> getBindings()[$interface]) && static::getContainerInterface($container_name) -> getBindings()[$interface] != null);
    }

    /**
     * 清空容器
     * @param string $container_name
     */
    public static function clearContainerInterface($container_name = '')
    {
        self::$container[$container_name] = new Container;
    }
}