<?php
namespace Itxiao6\Framework\Facade\Environment;
use Illuminate\Container\Container;

/**
 * 环境自适应
 * Interface EnvironmentInsterface
 * @package Itxiao6\Framework\Facade\Environment
 */
interface EnvironmentInsterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}