<?php
namespace CloverSwoole\CloverSwoole\Facade\Timer;
use CloverSwoole\CloverSwoole\Facade\SuperClosure\Invoker;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManage;

/**
 * 定时器
 * Class Timer
 * @package CloverSwoole\CloverSwoole\Facade\Timer
 */
class Timer
{
    /**
     * 创建一个循环任务
     * @param $microSeconds
     * @param \Closure $func
     * @param null $args
     * @return mixed
     */
    public static function loop($microSeconds,\Closure $func,$args = null){
        $new = function (...$args)use($func){
            try{
                Invoker::callUserFunc($func,...$args);
            }catch (\Throwable $throwable){
                dump($throwable);
            }
        };
        return ServerManage::getInterface()->getRawServer()->tick($microSeconds,$new,$args);
    }

    /**
     * 延时执行
     * @param $microSeconds
     * @param \Closure $func
     * @param null $args
     * @return mixed
     */
    public static function delay($microSeconds,\Closure $func,$args = null){
        $new = function (...$args)use($func){
            try{
                Invoker::callUserFunc($func,...$args);
            }catch (\Throwable $throwable){
                dump($throwable);
            }
        };
        return ServerManage::getInterface()->getRawServer()->after($microSeconds,$new,$args);
    }

    /**
     * 清除定时器
     * @param $timerId
     */
    public static function clear($timerId){
        ServerManage::getInterface()->getRawServer()->clearTimer($timerId);
    }
}