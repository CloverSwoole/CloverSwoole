<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\SwooleHttp\EasySwoole\WebService;

/**
 * Swoole Http 组件
 * Class SwooleHttp
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class SwooleHttp implements SwooleHttpInterface
{
    /**
     * @param Container|null $container
     */
    public static function boot(?Container $container = null)
    {
        $http = new \swoole_http_server("0.0.0.0", 9501);
        $http->set([
            'worker_num'=>1
        ]);
        $http->on("start", function ($server) {
            echo "Swoole http server is started at http://127.0.0.1:9501\n";
        });
        $service = new WebService('App\\Http\\Controller\\',5,100,$container);
        $service->setExceptionHandler(function (\Throwable $throwable,\EasySwoole\Http\Request $request,\EasySwoole\Http\Response $response){
            $response->write('error:'.$throwable->getMessage());
        });
        $http->on("request", function ($request, $response)use($service,$container) {
            $req = new \EasySwoole\Http\Request($request);
            $service->onRequest($req,new \EasySwoole\Http\Response($response));
        });
        $http->start();
    }
}