<?php
namespace Config;
use Illuminate\Container\Container;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ConfigInterface;

/**
 * Swoole Http 配置
 * Class SwooleHttpConfig
 * @package Config
 */
class SwooleHttpConfig implements ConfigInterface
{

    public function boot()
    {
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http'] = [
            'port'=>5200,
            'host'=>'0.0.0.0',
            'server'=>[
                'worker_num'=>32,
                'task_worker_num'=>8,
                'daemonize'=>false,
                'max_request'=>50,
                'pid_file'=>__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Temp'.DIRECTORY_SEPARATOR.'swoole_http_pid.pid',
            ],
        ];
    }
}