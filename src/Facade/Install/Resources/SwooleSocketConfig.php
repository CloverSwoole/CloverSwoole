<?php
namespace Config;
use Illuminate\Container\Container;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ConfigInterface;

/**
 * Swoole Socket 配置
 * Class SwooleSocketConfig
 * @package Config
 */
class SwooleSocketConfig implements ConfigInterface
{

    /**
     * @return mixed|void
     */
    public function boot()
    {
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_socket'] = [
            'port'=>5200,
            'host'=>'0.0.0.0',
            'server'=>[
                'worker_num'=>50,
                'task_worker_num'=>8,
                'daemonize'=>false,
                'pid_file'=>__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Temp'.DIRECTORY_SEPARATOR.'http_swoole_pid.pid',
            ],
        ];
    }
}