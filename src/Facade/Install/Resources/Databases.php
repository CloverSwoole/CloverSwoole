<?php
namespace Config;
/**
 * 自定义数据库配置
 * Class Datbases
 * @package Config
 */
class Databases implements \CloverSwoole\CloverSwoole\Facade\Databases\ConfigInterface
{
    public function boot()
    {
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['database'] = [
            'driver'=>'mysql',
            'host'=>env('db_host','localhost'),
            'database'=>env('db_name','test'),
            'username'=>env('db_user','root'),
            'password'=>env('db_pass','123456'),
            'charset'=>'utf8',
            'collation'=>'utf8_general_ci',
            'prefix'=>''
        ];
    }
}