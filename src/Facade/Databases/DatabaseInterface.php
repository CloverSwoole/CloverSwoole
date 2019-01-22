<?php
namespace CloverSwoole\CloverSwoole\Facade\Databases;
use Illuminate\Container\Container;

/**
 * 数据库服务
 * Interface Database
 * @package CloverSwoole\CloverSwoole\Facade\Databases
 */
interface DatabaseInterface
{
    public function boot(?Container $container = null);
}