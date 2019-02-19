<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\Command\Command;
use CloverSwoole\CloverSwoole\Facade\Route\HttpServer;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\EasySwoole\WebService;
use CloverSwoole\CloverSwoole\Framework;

/**
 * Swoole Http 组件
 * Class SwooleHttp
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class SwooleHttp implements SwooleHttpInterface
{
    /**
     * Swoole Http 组件
     */
    public function boot()
    {
        /**
         * 初始化组件配置
         */
        \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ConfigInterface::class) -> boot();
        /**
         * 解析命令
         */
        list($command,$options) = Command::commandHandler();
        /**
         * 处理命令
         */
        switch ($command) {
            case 'start':
                $this -> start($options);
                break;
            case 'stop':
                $this -> stop($options);
                break;
            case 'reload':
                $this -> reload($options);
                echo "Socket and Server Reloaded at ".date('Y-m-d H:i:s')."\n";
                break;
            case 'restart':
                $this -> restart($options);
                echo "Socket and Server Restarted at ".date('Y-m-d H:i:s')."\n";
                break;
            case 'help':
                $this -> help($options);
                break;
            default:
                $this -> help($options);
                break;
        }
    }

    /**
     * 启动服务
     * @param $options
     */
    protected function start($options)
    {
        /**
         * 获取swoole_http 配置
         */
        $swoole_http = \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http'];
        /**
         * 判断是否自定义端口
         */
        if(isset($options['p']) && $options['p']>0){
            $swoole_http['port'] = $options['p'];
        }
        /**
         * 判断是否需要自定义监听ip
         */
        if(isset($options['h']) && $options['h']>0){
            $swoole_http['host'] = $options['h'];
        }
        /**
         * 获取server
         */
        $http = new \Swoole\Http\Server($swoole_http['host'], $swoole_http['port']);
        /**
         * 判断参数是否存在
         */
        if(is_array($swoole_http['server']) && count($swoole_http['server']) > 0){
            /**
             * 获取http server 配置项
             */
            $http_server = $swoole_http['server'];
            /**
             * 判断是否要以守护进程运行
             */
            if(count($options) && isset($options['d'])){
                $http_server['daemonize'] = true;
            }
            /**
             * 判断是否要自定义pid进程号存储id
             */
            if(isset($options['pid']) && file_exists($options['pid'])){
                $http_server['pid_file'] = $options['pid'];
            }
            /**
             * 设置参数
             */
            $http->set($http_server);
        }
        /**
         * 获取socket 事件模型
         */
        $socket_event = \CloverSwoole\CloverSwoole\Framework::getContainerInterface() -> make(ServerEventInterface::class) -> boot();
        /**
         * 监听服务 启动事件
         */
        $http->on("start", [$socket_event,'onStart']);
        /**
         * 异步投递任务
         */
        $http->on("Task", [$socket_event,'onTask']);
        /**
         * 异步任务完成
         */
        $http->on("Finish", [$socket_event,'onFinish']);
        /**
         * 监听服务 启动事件
         */
        $http->on("request", function(\swoole_http_request $request_raw, \swoole_http_response $response_raw)use($socket_event,$http){
            $socket_event -> onRequest($request_raw,$response_raw,$http);
        });
        /**
         * 启动服务
         */
        $http->start();
    }

    /**
     * 停止服务
     * @param $options
     * @return bool
     */
    protected function stop($options)
    {
        /**
         * 判断是否要自定义pid进程号存储id
         */
        if(isset($options['pid']) && file_exists($options['pid'])){
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file'] = $options['pid'];
        }
        if((!isset(\CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file'])) || (!file_exists(\CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file']))){
            echo "pid files no exists\n";
            return ;
        }
        $pid = file_get_contents(\CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file']);
        if (!\Swoole\Process::kill($pid, 0)) {
            echo "PID :{$pid} not exist \n";
            return false;
        }
        /**
         * 判断是否为强制杀掉
         */
        if (in_array('-f', $options)) {
            \Swoole\Process::kill($pid, SIGKILL);
        } else {
            \Swoole\Process::kill($pid);
        }
    }

    /**
     * 重启服务
     * @param $options
     */
    public function restart($options)
    {
        $this -> stop($options);
        $this -> start($options);
    }

    /**
     * 重载服务
     * @param $options
     */
    public function reload($options)
    {
        /**
         * 判断是否要自定义pid进程号存储id
         */
        if(isset($options['pid']) && file_exists($options['pid'])){
            \CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file'] = $options['pid'];
        }
        if((!isset(\CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file'])) || (!file_exists(\CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file']))){
            echo "pid files no exists\n";
            return ;
        }
        /**
         * 获取pid
         */
        $pid = file_get_contents(\CloverSwoole\CloverSwoole\Framework::getContainerInterface()['config']['swoole_http']['server']['pid_file']);
        /**
         * 重启进程
         */
        \Swoole\Process::kill($pid,SIGUSR1);
    }

    /**
     * 帮助命令
     * @param $options
     */
    protected function help($options)
    {
        echo "帮助命令\n";
    }
}