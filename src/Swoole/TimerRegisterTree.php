<?php
namespace CloverSwoole\Swoole;
/**
 * 定时器注册树
 * Class TimerRegisterTree
 * @package CloverSwoole\Swoole
 */
class TimerRegisterTree
{
    /**
     * 实例
     * @var null
     */
    protected static $interface = null;

    /**
     * 定时器
     * @var array
     */
    protected $timer = [];

    /**
     * 获取定时器注册数
     * @return TimerRegisterTree|null
     */
    public static function getInterface()
    {
        if(self::$interface == null){
            return (new static(...func_get_args()));
        }
        return self::$interface;
    }

    /**
     * 设置全局可访问
     * @return $this
     */
    public function setAsGlobal()
    {
        self::$interface = $this;
        return $this;
    }

    /**
     * 循环获取定时器
     * @return \Generator
     */
    public function getTimerItem()
    {
        foreach ($this -> timer as $item){
            yield $item;
        }
    }

    /**
     * 添加定时器
     * @param $ms
     * @param $callback
     * @param null|\Closure $success_callback
     * @return $this
     */
    public function addTimer($ms,$callback,$success_callback = null)
    {
        /**
         * 完成回调
         */
        if($success_callback == null && (!$success_callback instanceof \Closure)){
            throw new \Exception('未设置任务');
            $success_callback = function(){};
        }
        /**
         * 任务内容
         */
        if(!($callback instanceof \Closure)){
            throw new \Exception('未设置任务完成回调');
            $callback = function(){};
        }
        $this -> timer[] = ['ms'=>$ms,'callback'=>$callback,'success_callback'=>$success_callback];
        return $this;
    }
}