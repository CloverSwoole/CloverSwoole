<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use Illuminate\Container\Container;

/**
 * SwooleHttp配置
 * Interface ConfigInterface
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}