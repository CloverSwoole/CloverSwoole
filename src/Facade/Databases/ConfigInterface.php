<?php
namespace Itxiao6\Framework\Facade\Databases;
use Illuminate\Container\Container;

/**
 * Interface ConfigInterface
 * @package Itxiao6\Framework\Facade\Databases
 */
interface ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed
     */
    public function boot(?Container $container = null);
}