<?php
namespace App\Event;
use CloverSwoole\CloverSwoole\Facade\SuperClosure\SuperClosure;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\HttpServerInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManage;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManageInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\Exception\Exception;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\ServerEventInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SocketFrame;
use CloverSwoole\CloverSwoole\Facade\Whoops\WhoopsInterface;

/**
 * Swoole Socket 事件模型
 * Class SwooleSocketEvent
 * @package App\Event
 */
class SwooleSocketEvent implements ServerEventInterface
{
    /**
     * 获取容器
     * @return $this
     */
    public function boot()
    {
        return $this;
    }
    /**
     * 服务关闭
     * @param $server
     * @param int $fd
     * @return mixed|void
     */
    public function onClose($server,$fd)
    {
        try{
            echo "连接关闭:{$fd} \n";
        }catch (\Throwable $exception){
            /**
             * 处理异常
             */
            echo "异常:{$exception -> getMessage()}\n";
        }
    }

    /**
     * 服务关闭
     * @param \swoole_websocket_server $server
     * @param \swoole_http_request $request
     * @return mixed|void
     */
    public function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        try{
            /**
             * 获取全局Server 实例
             */
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
            echo "连接到达\n";
        }catch (\Throwable $exception){
            echo "异常:{$exception -> getMessage()}\n";
        }
    }

    /**
     * 消息到达
     * @param \swoole_websocket_server $server
     * @param \swoole_websocket_frame $frame
     * @return mixed|void
     */
    public function onMessage(\swoole_websocket_server $server, \swoole_websocket_frame $frame)
    {
        try{
            /**
             * 获取全局Server 实例
             */
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
            /**
             * 设置全局访问消息
             */
            (new SocketFrame()) -> boot($frame) -> setAsGlobal(true);
            /**
             * 判断信息是否正确
             */
            if(!(SocketFrame::getInterface() -> getOpcode() == 1 && SocketFrame::getInterface() -> getFinish() == true && strlen(SocketFrame::getInterface() -> getData()) > 0)){
                throw new Exception('数据非法');
            }
            /**
             * 格式化数据
             */
            $data = json_decode(SocketFrame::getInterface() -> getData(),1);
            /**
             * 应用名过滤
             */
            if((!isset($data['app'])) || strlen($data['app']) < 1){
                $data['app'] = 'Index';
//                throw new Exception('应用不存在');
            }
            /**
             * 模块过滤
             */
            if((!isset($data['controller'])) || strlen($data['controller']) < 1){
                $data['controller'] = 'Index';
//                throw new Exception('模块不存在');
            }
            /**
             * 操作不存在
             */
            if((!isset($data['action'])) || strlen($data['action']) < 1){
                $data['action'] = 'index';
//                throw new Exception('操作不存在');
            }
            /**
             * 获取消息的操作目的
             */
            $class = '\App\Socket\\'.$data['app'].'\\'.$data['controller'];
            /**
             * 判断控制器是否存在
             */
            if(!class_exists($class)){
                throw new Exception('找不到指定控制器:'.$class);
            }
            /**
             * 实例化控制器
             */
            (new $class(ServerManage::getInterface(),SocketFrame::getInterface())) -> __hook($data['action']);
        }catch (\Throwable $exception){
            dump($exception);
            /**
             * 处理异常
             */
            echo "异常:{$exception -> getMessage()}\n";
        }
    }

    /**
     * 消息到达
     * @param \swoole_http_request $request_raw
     * @param \swoole_http_response $response_raw
     * @param \swoole_websocket_server $server
     * @return mixed|void
     */
    public function onRequest(\swoole_http_request $request_raw, \swoole_http_response $response_raw,\swoole_websocket_server $server)
    {
        try{
            /**
             * 获取全局Server 实例
             */
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
            /**
             * 实例化WebServer
             */
            $http_service = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(HttpServerInterface::class) -> boot();
            /**
             * 请求到达
             */
            $http_service -> onRequest($request_raw,$response_raw);
        }catch (\Throwable $exception){
            /**
             * 获取 request
             */
            $request = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Request::class) -> boot($request_raw);
            /**
             * 获取 response
             */
            $response = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(\CloverSwoole\CloverSwoole\Facade\Http\Response::class) -> boot($response_raw);
            /**
             * 处理异常
             */
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(WhoopsInterface::class) -> swooleOnRequestException($exception,$request,$response);
        }
    }

    /**
     * 服务关闭
     * @param \swoole_websocket_server $server
     * @return mixed|void
     */
    public function onShutdown(\swoole_websocket_server $server)
    {
        /**
         * 获取全局Server 实例
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
        echo "WebAndSocketOnShutdownEd\n";
    }

    /**
     * 服务启动
     * @param \swoole_websocket_server $server
     * @return mixed|void
     */
    public function onStart(\swoole_websocket_server $server)
    {
        /**
         * 获取全局Server 实例
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerManageInterface::class) -> boot($server) -> setAsGlobal(true);
        echo "WebAndSocketOnStarted\n";
    }
    public function onTask(\swoole_http_server $server, int $taskId, int $fromWorkerId,$data)
    {
        if($data instanceof SuperClosure){
            try{
                return $data($server,$taskId,$fromWorkerId);
            }catch (\Throwable $throwable){
                dump($throwable);
            }
        }
    }
    public function onFinish(\swoole_http_server $serv, int $task_id, string $data)
    {
        dump($data);
    }
}