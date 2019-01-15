<?php
namespace Itxiao6\Framework\Facade\Whoops;
use Itxiao6\Framework\Facade\Http\Request;
use Itxiao6\Framework\Facade\Http\Response;
use Whoops\Handler\HandlerInterface;

/**
 * Class Whoops
 * @package Itxiao6\Framework\Facade\Whoops
 */
class Whoops implements WhoopsInterface
{
    /**
     * @var null | \Throwable
     */
    protected $exception = null;

    public function boot(\Throwable $exception)
    {

    }

    /**
     * Swoole 请求异常处理
     * @param \Throwable $exception
     * @param \Swoole\Http\Request $request
     * @param \Swoole\Http\Response $response
     */
    public function swooleOnRequestException(\Throwable $exception,\Swoole\Http\Request $request,\Swoole\Http\Response $response)
    {
//        try{
//            $response -> writeContent('异常:'.$exception -> getMessage());
//            $response -> endResponse();
//        }catch (\Throwable $throwable){
//            dump($throwable);
//        }
    }
}