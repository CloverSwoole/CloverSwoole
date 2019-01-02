<?php
namespace Itxiao6\Framework\Facade\Databases;
use Illuminate\Container\Container;

/**
 * 数据库服务
 * Interface Database
 * @package Itxiao6\Framework\Facade\Databases
 */
interface DatabaseInterface
{
    public static function boot(?Container $container = null);
}