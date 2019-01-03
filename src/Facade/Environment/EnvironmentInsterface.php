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
     * @param string|null $base_dir
     * @return mixed
     */
    public function boot(?Container $container = null,$base_dir = null);
}