<?php
namespace Itxiao6\Framework\Facade\Route;

/**
 * 路由配置
 * Class Config
 * @package Itxiao6\Framework\Facade\Route
 */
class Config implements ConfigInterface
{
    public function boot(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $container['config']['route'] = [
            // 控制器的记录路径
            'controllerNameSpace'=>'App\\Http\\Controller\\',
            // 进程控制器数量
            'maxPoolNum'=>15,
            // 最大深度
            'maxDepth'=>5,
        ];
    }
}