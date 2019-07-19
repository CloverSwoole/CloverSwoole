<?php
namespace CloverSwoole\Databases;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use \Illuminate\Database\Connection;

/**
 * Class DB
 * @package CloverSwoole\Databases
 * @method void beginTransaction
 * @method void createTransaction
 * @method void commit
 * @method void rollBack
 * @method void transaction
 * @mixin \Illuminate\Database\Concerns\ManagesTransactions
 */
class DB extends Manager
{

    public function getConnection($name = null)
    {
        DbConfig::getInterface() -> checkDatabsesConnection();
        return parent::getConnection($name);
    }
    public static function schema($connection = null)
    {
        DbConfig::getInterface() -> checkDatabsesConnection();
        return parent::schema($connection);
    }
    public static function table($table, $connection = null)
    {
        DbConfig::getInterface() -> checkDatabsesConnection();
        return parent::table($table, $connection);
    }
    public static function connection($connection = null)
    {
        DbConfig::getInterface() -> checkDatabsesConnection();
        return parent::connection($connection);
    }
    public static function __callStatic($method, $parameters)
    {
        DbConfig::getInterface() -> checkDatabsesConnection();
        return parent::__callStatic($method, $parameters);
    }
    public function __construct(?Container $container = null)
    {
        DbConfig::getInterface() -> checkDatabsesConnection();
        parent::__construct($container);
    }
}