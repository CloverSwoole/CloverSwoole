<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
use Illuminate\Container\Container;
use CloverSwoole\CloverSwoole\Facade\Command\Command;

/**
 * SWOOLE Socket 组件
 * Class SwooleSocket
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
class SwooleSocket
{
    /**
     * 应用容器
     * @var null|Container
     */
    protected $container = null;
    /**
     * 启动服务
     * @param Container|null $container
     */
    public function boot(?Container $container = null)
    {
        /**
         * 检查容器
         */
        if(!($container instanceof Container)){
            $container = new Container();
        }
        /**
         * 获取容器
         */
        $this -> container = $container;
        /**
         * 获取配置
         */
        $this -> container -> make(ConfigInterface::class) -> boot($this -> container);
        /**
         * 初始化组件配置
         */
        $this -> container -> make(ConfigInterface::class) -> boot($this -> container);
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
    public function start($options)
    {
        /**
         * 获取server
         */
        $http = new \Swoole\WebSocket\Server($this -> container['config']['swoole_socket']['host'], $this -> container['config']['swoole_socket']['port']);
        /**
         * 判断参数是否存在
         */
        if(is_array($this -> container['config']['swoole_socket']['server']) && count($this -> container['config']['swoole_socket']['server']) > 0){
            /**
             * 设置参数
             */
            $http->set($this -> container['config']['swoole_socket']['server']);
        }
        /**
         * 获取socket 事件模型
         */
        $socket_event = $this -> container -> make(ServerEventInterface::class) -> boot($this -> container);
        /**
         * 监听请求到达 事件
         */
        $http->on("request", function(\swoole_http_request $request_raw, \swoole_http_response $response_raw)use($socket_event,$http){
            $socket_event -> onRequest($request_raw,$response_raw,$http);
        });
        /**
         * 监听连接到达 事件
         */
        $http -> on('open',[$socket_event,'onOpen']);
        /**
         * 消息到达 事件
         */
        $http -> on('message',[$socket_event,'onMessage']);
        /**
         * 监听关闭 事件
         */
        $http -> on('close',[$socket_event,'onClose']);
        /**
         * 服务启动 事件
         */
        $http -> on('start',[$socket_event,'onStart']);
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
        if((!isset($this -> container['config']['swoole_socket']['server']['pid_file'])) || (!file_exists($this -> container['config']['swoole_socket']['server']['pid_file']))){
            echo "pid files no exists\n";
            return ;
        }
        $pid = file_get_contents($this -> container['config']['swoole_socket']['server']['pid_file']);
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
        if((!isset($this -> container['config']['swoole_socket']['server']['pid_file'])) || (!file_exists($this -> container['config']['swoole_socket']['server']['pid_file']))){
            echo "pid files no exists\n";
            return ;
        }
        $pid = file_get_contents($this -> container['config']['swoole_socket']['server']['pid_file']);
        \Swoole\Process::kill($pid,SIGUSR1);
    }

    /**
     * 帮助命令
     * @param $options
     */
    protected function help($options)
    {
        echo "Swoole Socket 帮助命令\n";
    }
}