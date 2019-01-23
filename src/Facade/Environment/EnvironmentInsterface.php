<?php
namespace CloverSwoole\CloverSwoole\Facade\Environment;

/**
 * 环境自适应
 * Interface EnvironmentInsterface
 * @package CloverSwoole\CloverSwoole\Facade\Environment
 */
interface EnvironmentInsterface
{
    /**
     * @param string|null $base_dir
     * @return mixed
     */
    public function boot($base_dir = null);
}