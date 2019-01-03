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
    public function boot(?Container $container = null)
    {
        /**
         * 获取配置
         */
        $container -> make(Config::class) -> boot($container);
        /**
         * 获取server
         */
        $http = new \Swoole\Http\Server($container['config']['swoole_http']['host'], $container['config']['swoole_http']['port']);
        /**
         * 判断参数是否存在
         */
        if(is_array($container['config']['swoole_http']['server']) && count($container['config']['swoole_http']['server']) > 0){
            /**
             * 设置参数
             */
            $http->set($container['config']['swoole_http']['server']);
        }
        /**
         * 监听启动事件
         */
        $http->on("start", function ($server) use($container){
            echo "Swoole http server is started at http://127.0.0.1:{$container['config']['swoole_socket']['port']}\n";
        });
        /**
         * 实例化WebServer
         */
        $service = new WebService('App\\Http\\Controller\\',5,100,$container);
        /**
         * 设置异常处理程序
         */
        $service->setExceptionHandler(function (\Throwable $throwable,\EasySwoole\Http\Request $request,\EasySwoole\Http\Response $response){
            $response->write('msg:'.$throwable->getMessage().'file:'.$throwable->getFile().'line:'.$throwable->getLine());
        });
        /**
         * 监听请求到达 事件
         */
        $http->on("request", function ($request, $response)use($service,$container) {
            $req = new \EasySwoole\Http\Request($request);
            $service->onRequest($req,new \EasySwoole\Http\Response($response));
        });
        /**
         * 启动服务
         */
        $http->start();
    }
}