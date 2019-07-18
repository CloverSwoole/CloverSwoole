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
        'routeExceptionHandler'=>null
    ];
    public static function getInterface()
    {
        return (new static());
    }
    /**
     * 设置路由异常处理
     * @param $callback
     */
    public function setRouteExceptionHandler($callback)
    {
        $this->exception_handler['routeExceptionHandler'] = $callback;
    }

    /**
     * 捕获处理路由异常
     * @param \Throwable $throwable
     */
    public function catchRoute(\Throwable $throwable)
    {
        Response::getInterface() -> withContent('路由异常:'.$throwable -> getMessage());
        Response::getInterface() -> endResponse();
    }

    /**
     * 捕获控制器异常
     * @param \Throwable $throwable
     */
    public function catchController(\Throwable $throwable)
    {
        Response::getInterface() -> withContent('异常:'.$throwable -> getMessage());
        Response::getInterface() -> endResponse();
    }
}