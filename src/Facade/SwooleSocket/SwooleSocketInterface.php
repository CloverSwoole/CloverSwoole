<?php
namespace Itxiao6\Framework\Facade\SwooleSocket;
use Illuminate\Container\Container;

/**
 * SWOOLE Socket 接口
 * Interface SwooleSocketInterface
 * @package Itxiao6\Framework\Facade\SwooleSocket
 */
interface SwooleSocketInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}