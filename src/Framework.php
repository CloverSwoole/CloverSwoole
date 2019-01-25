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
     * @var null|Container
     */
    protected static $container = null;
    /**
     * @return Container|null
     */
    public static function getContainerInterface()
    {
        if(!(self::$container instanceof Container)){
            self::$container = new Container;
        }
        return self::$container;
    }

    /**
     * 清空容器
     */
    public static function clearContainerInterface()
    {
        self::$container = new Container;
    }
}