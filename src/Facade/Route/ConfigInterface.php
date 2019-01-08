<?php
namespace Itxiao6\Framework\Facade\Route;

use Illuminate\Container\Container;

/**
 * 路由配置
 * Interface ConfigInterface
 * @package Itxiao6\Framework\Facade\Route
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}