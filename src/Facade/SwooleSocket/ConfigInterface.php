<?php
namespace Itxiao6\Framework\Facade\SwooleSocket;
use Illuminate\Container\Container;

/**
 * Interface ConfigInterface
 * @package Itxiao6\Framework\Facade\SwooleSocket
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}