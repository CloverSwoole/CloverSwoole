<?php
namespace CloverSwoole\CloverSwoole\Facade\Environment;
use Illuminate\Container\Container;

/**
 * 环境自适应
 * Interface EnvironmentInsterface
 * @package CloverSwoole\CloverSwoole\Facade\Environment
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