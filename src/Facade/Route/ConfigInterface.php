<?php
namespace CloverSwoole\CloverSwoole\Facade\Route;

use Illuminate\Container\Container;

/**
 * 路由配置
 * Interface ConfigInterface
 * @package CloverSwoole\CloverSwoole\Facade\Route
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}