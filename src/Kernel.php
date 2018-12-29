<?php
namespace Itxiao6\Framework;
use Illuminate\Container\Container;
/**
 * Class Kernel
 * @package Itxiao6\Framework
 */
class Kernel
{
    /**
     * 初始化框架
     * @param Container|null $container
     * @return Container|null
     */
    public static function getInterface(?Container $container = null)
    {
        /**
         * 判断是否传入了服务容器
         */
        if(!($container instanceof Container)){
            $container = new Container();
        }
        /**
         * 注入框架入口
         */
        return $container;
    }
}