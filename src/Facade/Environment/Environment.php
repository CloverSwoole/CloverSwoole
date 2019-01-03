<?php
namespace Itxiao6\Framework\Facade\Environment;
use Illuminate\Container\Container;

/**
 * 环境自适应
 * Class Environment
 * @package Itxiao6\Framework\Facade\Environment
 */
class Environment implements EnvironmentInsterface
{
    /**
     * @param Container|null $container
     * @return mixed|void
     */
    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
    }
}