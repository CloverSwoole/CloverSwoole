<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;

/**
 * Swoole Http 组件门面
 * Interface SwooleHttpInterface
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
interface SwooleHttpInterface
{
    public function boot(?Container $container = null);
}