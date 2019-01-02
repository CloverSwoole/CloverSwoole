<?php
namespace Itxiao6\Framework\Facade\SwooleHttp\EasySwoole;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Illuminate\Container\Container;

class WebService
{
    private $dispatcher;
    function __construct($controllerNameSpace = 'App\\HttpController\\',$depth = 5,$maxPoolNum = 100,?Container $container = null)
    {

        $this->dispatcher = new Dispatcher($controllerNameSpace,$depth,$maxPoolNum,$container);
    }

    function setExceptionHandler(callable $handler)
    {
        $this->dispatcher->setHttpExceptionHandler($handler);
    }

    function onRequest(Request $request_psr,Response $response_psr):void
    {
        $this->dispatcher->dispatch($request_psr,$response_psr);
        $response_psr->__response();
    }
}