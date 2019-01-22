<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use Illuminate\Container\Container;

/**
 * Swoole Http 组件门面
 * Interface SwooleHttpInterface
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
interface SwooleHttpInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}