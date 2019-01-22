<?php
namespace CloverSwoole\CloverSwoole\Facade\Whoops;
use CloverSwoole\CloverSwoole\Facade\Http\Request;
use CloverSwoole\CloverSwoole\Facade\Http\Response;
use Whoops\Handler\HandlerInterface;

/**
 * Class Whoops
 * @package CloverSwoole\CloverSwoole\Facade\Whoops
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
     * @param \Throwable $throwable
     * @param Request $request
     * @param Response $response
     */
    public function swooleOnRequestException(\Throwable $throwable,Request $request,Response $response)
    {
        try{
            /**
             * 异常消息
             */
            $response -> writeContent('异常:'.$throwable -> getMessage()."<br />\n");
            /**
             * 抛出文件
             */
            $response -> writeContent('文件:'.$throwable -> getFile()."<br />\n");
            /**
             * 抛出位置
             */
            $response -> writeContent('位置:'.$throwable -> getLine()."<br />\n");
            /**
             * 结束请求
             */
            $response -> endResponse();
        }catch (\Throwable $throwable){
            dump($throwable);
        }
    }
}