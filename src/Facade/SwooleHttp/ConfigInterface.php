<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;

/**
 * SwooleHttp配置
 * Interface ConfigInterface
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public static function boot(?Container $container = null);
}