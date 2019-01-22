<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use Illuminate\Container\Container;

/**
 * 默认配置实例
 * Class Config
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class Config implements ConfigInterface
{

    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $container['config']['swoole_http'] = [
            'port'=>5200,
            'host'=>'0.0.0.0',
            'server'=>[
                'worker_num'=>50,
                'daemonize'=>false,
                'pid_file'=>'',
            ],
        ];
    }
}