<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
use Illuminate\Container\Container;

/**
 * SWOOLE Socket 接口
 * Interface SwooleSocketInterface
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
interface SwooleSocketInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}