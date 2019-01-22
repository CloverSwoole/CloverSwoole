<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
use Illuminate\Container\Container;

/**
 * Interface ConfigInterface
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}