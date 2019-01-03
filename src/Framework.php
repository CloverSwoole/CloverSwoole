<?php
namespace Itxiao6\Framework;
use Illuminate\Container\Container;

/**
 * 框架入口
 * Class Framework
 * @package Itxiao6\Framework
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

}