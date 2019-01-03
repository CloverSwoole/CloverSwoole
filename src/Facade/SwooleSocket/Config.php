<?php
namespace Itxiao6\Framework\Facade\SwooleSocket;

use Illuminate\Container\Container;

/**
 * Class Config
 * @package Itxiao6\Framework\Facade\SwooleSocket
 */
class Config implements SwooleSocketInterface
{
    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $container['config']['swoole_socket'] = [
            'port'=>5200,
            'host'=>'0.0.0.0',
            'server'=>[
                'worker_num'=>50,
                'daemonize'=>false,
            ],
        ];
    }
}