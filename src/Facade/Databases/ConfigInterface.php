<?php
namespace CloverSwoole\CloverSwoole\Facade\Databases;
use Illuminate\Container\Container;

/**
 * Interface ConfigInterface
 * @package CloverSwoole\CloverSwoole\Facade\Databases
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}