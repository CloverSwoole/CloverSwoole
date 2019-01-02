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
    public static $container = null;
    public function start(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container;
        }
        self::$container = $container;
        echo "Framework Started\n";
    }

    /**
     * @return Container|null
     */
    public static function getContainerInterface()
    {
        return self::$container;
    }
}