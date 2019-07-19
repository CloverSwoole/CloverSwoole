<?php

namespace CloverSwoole\Exception;

use CloverSwoole\Http\Response;

/**
 * Class ExceptionHandler
 * @package CloverSwoole\Exception
 */
class ExceptionHandler
{
    /**
     * 异常处理
     * @var array
     */
    protected $exception_handler = [
        'routeExceptionHandler' => null,
        'timerExceptionHandler' => null,
        'taskExceptionHandler' => null,
        'controllerExceptionHandler' => null
    ];

    public static function getInterface()
    {
        return (new static());
    }

    /**
     * 设置路由异常处理
     * @param \Closure $callback
     */
    public function setRouteExceptionHandler(\Closure $callback)
    {
        $this->exception_handler['routeExceptionHandler'] = $callback;
    }

    /**
     * 捕获处理路由异常
     * @param \Throwable $throwable
     */
    public function catchRoute(\Throwable $throwable)
    {
        /**
         * 判断是否设置了自定义异常处理
         */
        if (!($this->exception_handler['routeExceptionHandler'] instanceof \Closure)) {
            Response::getInterface()->withContent('路由异常:' . $throwable->getMessage());
            Response::getInterface()->endResponse();
            return;
        }
        /**
         * 调用自定义异常处理
         */
        $this->exception_handler['routeExceptionHandler'](...func_get_args());
    }

    /**
     * 修改控制器异常处理程序
     * @param \Closure $callback
     */
    public function setControllerExceptionHandler(\Closure $callback)
    {
        $this->exception_handler['controllerExceptionHandler'] = $callback;
    }

    /**
     * 捕获控制器异常
     * @param \Throwable $throwable
     */
    public function catchController(\Throwable $throwable)
    {
        if (!($this->exception_handler['controllerExceptionHandler'] instanceof \Closure)) {
            Response::getInterface()->withContent('异常:' . $throwable->getMessage());
            Response::getInterface()->endResponse();
            return;
        }
        $this->exception_handler['controllerExceptionHandler'](...func_get_args());
    }

    /**
     * 设置定时器异常处理程序
     * @param \Closure $callback
     */
    public function setTimerExceptionHandler(\Closure $callback)
    {
        $this->exception_handler['timerExceptionHandler'] = $callback;
    }

    /**
     * 捕获定时器异常处理程序
     * @param \Throwable $throwable
     */
    public function catchTimer(\Throwable $throwable)
    {
        if (!($this->exception_handler['timerExceptionHandler'] instanceof \Closure)) {
            echo("定时器异常:" . $throwable->getMessage() . "\n");
            return;
        }
        $this->exception_handler['timerExceptionHandler'](...func_get_args());
    }

    /**
     * 设置Task异常处理程序
     * @param \Closure $callback
     */
    public function setTaskExceptionHandler(\Closure $callback)
    {
        $this->exception_handler['taskExceptionHandler'] = $callback;
    }

    /**
     * 捕获Task任务异常
     * @param \Throwable $throwable
     */
    public function catchTask(\Throwable $throwable)
    {
        if (!($this->exception_handler['taskExceptionHandler'] instanceof \Closure)) {
            echo("任务异常:" . $throwable->getMessage() . "\n");
            return;
        }
        $this->exception_handler['taskExceptionHandler'](...func_get_args());
    }
}