<?php
namespace CloverSwoole\CloverSwoole\Facade\Databases;

/**
 * Class Config
 * @package CloverSwoole\CloverSwoole\Facade\Databases
 */
class Config implements ConfigInterface
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['database'] = [
            'driver'=>'mysql',
            'host'=>'localhost',
            'database'=>'test',
            'username'=>'root',
            'password'=>'',
            'charset'=>'utf8',
            'collation'=>'utf8_general_ci',
            'prefix'=>''
        ];
    }
}