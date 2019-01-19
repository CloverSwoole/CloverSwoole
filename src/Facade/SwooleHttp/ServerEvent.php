<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Whoops\WhoopsInterface;
use Itxiao6\Framework\Framework;
/**
 * 服务事件模型
 * Class ServerEvent
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class ServerEvent implements ServerEventInterface
{
    /**
     * 服务容器
     * @var null|Container
     */
    protected $container = null;

    /**
     * 获取容器
     * @param Container|null $container
     * @return $this
     */
    public function boot(?Container $container = null)
    {
        /**
         * 判断容器是否有效
         */
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $this -> container = $container;
        return $this;
    }
    /**
     * 服务启动
     * @param \Swoole\Http\Server $server
     * @return mixed|void
     */
    public function onStart(\Swoole\Http\Server $server)
    {
        echo "WebServerOnStarted\n";
    }
    /**
     * 消息到达
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     * @return mixed|void
     */
    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        try{
            /**
             * 实例化WebServer
             */
            $http_service = $this -> container -> make(HttpServerInterface::class) -> boot($this -> container);
            /**
             * 请求到达
             */
            $http_service -> onRequest($request,$response);
        }catch (\Throwable $exception){
            $this -> container -> make(WhoopsInterface::class) -> swooleOnRequestException($request,$response);
        }
    }

    /**
     * 服务关闭
     * @param \Swoole\Http\Server $server
     * @return mixed|void
     */
    public function onShutdown(\Swoole\Http\Server $server)
    {
        echo "WebServerOnShutdownEd\n";
    }
    /**
     * 服务关闭
     * @param \Swoole\Http\Server $server
     * @param int $fd
     * @return mixed|void
     */
    public function onClose(\Swoole\Http\Server $server,$fd)
    {
        echo "WebServer Closed\n";
    }

    /**
     * 服务关闭
     * @param \Swoole\Http\Server $server
     * @param \Swoole\Http\Server $request
     * @return mixed|void
     */
    public function onOpen(\Swoole\Http\Server $server, \Swoole\Http\Server $request)
    {
        echo "WebServerOpOpened\n";
    }
}