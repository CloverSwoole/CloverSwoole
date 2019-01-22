<?php
namespace CloverSwoole\CloverSwoole\Facade\Databases;
use Illuminate\Container\Container;

/**
 * Class Config
 * @package CloverSwoole\CloverSwoole\Facade\Databases
 */
class Config implements ConfigInterface
{
    /**
     * @param Container|null $container
     * @return mixed|void
     */
    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $container['config']['database'] = [
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