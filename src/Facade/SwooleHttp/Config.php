<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;

/**
 * 默认配置实例
 * Class Config
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class Config implements ConfigInterface
{

    public function boot()
    {
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http'] = [
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