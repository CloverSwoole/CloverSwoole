<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
use CloverSwoole\CloverSwoole\Framework;

/**
 * Class SwooleHttpServer
 * Class Command
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
class Command extends \CloverSwoole\CloverSwoole\Facade\Command\Abstracts\Command
{
    /**
     * Server Config
     * @var array
     */
    protected $config = [];
    /**
     * 命令配置
     */
    protected function configure()
    {
        /**
         * 设置命令
         */
        $this -> setName('swoole_socket');
    }
    /**
     * 命令帮助
     */
    protected function help()
    {
        if(!(isset($this -> command[1]) && strlen($this -> command[1]) > 0)){
            echo "start:启动服务\nstop:停止服务\nreload:重载服务\nrestart:重启服务\nhelp:服务帮助\n";
            return;
        }
        switch($this -> command[1]){
            case 'start':
                echo "--d:守护进程启动\n--p-{端口}:指定端口启动\n";
                break;
            case 'stop':
                echo "--pid:指定进程号记录文件\n--pid-{文件绝对路径}:指定进程号记录文件\n";
                break;
            case 'reload':
                echo "--pid:指定进程号记录文件\n--pid-{文件绝对路径}:指定进程号记录文件\n";
                break;
            case 'restart':
                echo "--pid:指定进程号记录文件\n--pid-{文件绝对路径}:指定进程号记录文件\n";
                break;
            default:
                echo "start:启动服务\nstop:停止服务\nreload:重载服务\nrestart:重启服务\nhelp:服务帮助\n";
                break;
        }
    }
    /**
     * 构造器
     * Command constructor.
     * @param string $name
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name = '')
    {
        parent::__construct($name);
        /**
         * 默认启动器
         */
        if(!Framework::exists_bind(BootstrapInterface::class)){
            Framework::getContainerInterface() -> bind(BootstrapInterface::class,Bootstrap::class);
        }
        /**
         * 启动 启动器
         */
        Framework::getContainerInterface() -> make(Bootstrap::class) -> boot($this -> config);
    }
    /**
     * 启动服务
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function start()
    {
        /**
         * 判断是否自定义端口
         */
        if(isset($this -> options['p']) && $this -> options['p']>0){
            $this -> config['port'] = $this -> options['p'];
        }
        /**
         * 判断是否需要自定义监听ip
         */
        if(isset($this -> options['h']) && $this -> options['h']>0){
            $this -> config['host'] = $this -> options['h'];
        }
        /**
         * 获取server
         */
        $http = new \Swoole\WebSocket\Server($this -> config['host'], $this -> config['port']);
        /**
         * 判断参数是否存在
         */
        if(is_array($this -> config['server']) && count($this -> config['server']) > 0){
            /**
             * 获取http server 配置项
             */
            $http_server = $this -> config['server'];
            /**
             * 判断是否要以守护进程运行
             */
            if(count($this -> options) && isset($this -> options['d'])){
                $http_server['daemonize'] = true;
            }
            /**
             * 判断是否要自定义pid进程号存储id
             */
            if(isset($this -> options['pid']) && file_exists($this -> options['pid'])){
                $http_server['pid_file'] = $this -> options['pid'];
            }
            /**
             * 设置参数
             */
            $http->set($http_server);
        }
        /**
         * 处理默认事件模型
         */
        if(!Framework::exists_bind(ServerEventInterface::class)){
            Framework::getContainerInterface() -> bind(ServerEventInterface::class,ServerEvent::class);
        }
        /**
         * 启动事件模型
         * @var ServerEventInterface $socket_event
         */
        $socket_event = Framework::getContainerInterface() -> make(ServerEventInterface::class) -> boot();
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
         * 消息到达 事件
         */
        $http -> on('message',[$socket_event,'onMessage']);
        /**
         * 监听关闭 事件
         */
        $http -> on('close',[$socket_event,'onClose']);
        /**
         * 监听服务 启动事件
         */
        $http->on("request", function(\Swoole\Http\Request $request_raw, \Swoole\Http\Response $response_raw)use($socket_event,$http){
            $socket_event -> onRequest($request_raw,$response_raw,$http);
        });
        /**
         * 启动服务
         */
        $http->start();
    }

    /**
     * 停止服务
     * @return bool|void
     */
    protected function stop()
    {
        /**
         * 判断是否要自定义pid进程号存储id
         */
        if(isset($this -> options['pid']) && file_exists($this -> options['pid'])){
            Framework::getContainerInterface()['config']['swoole_socket']['server']['pid_file'] = $this -> options['pid'];
        }
        if((!isset(Framework::getContainerInterface()['config']['swoole_socket']['server']['pid_file'])) || (!file_exists(Framework::getContainerInterface()['config']['swoole_socket']['server']['pid_file']))){
            echo "pid files no exists\n";
            return ;
        }
        $pid = file_get_contents(Framework::getContainerInterface()['config']['swoole_socket']['server']['pid_file']);
        if (!\Swoole\Process::kill($pid, 0)) {
            echo "PID :{$pid} not exist \n";
            return false;
        }
        /**
         * 判断是否为强制杀掉
         */
        if (in_array('-f', $this -> options)) {
            \Swoole\Process::kill($pid, SIGKILL);
        } else {
            \Swoole\Process::kill($pid);
        }
    }

    /**
     * 重载服务
     */
    protected function reload()
    {
        /**
         * 判断是否要自定义pid进程号存储id
         */
        if(isset($this -> options['pid']) && file_exists($this -> options['pid'])){
            $this -> config['server']['pid_file'] = $this -> options['pid'];
        }
        if(!file_exists($this -> config['server']['pid_file'])){
            echo "pid files no exists\n";
            return ;
        }
        /**
         * 获取pid
         */
        $pid = file_get_contents($this -> config['server']['pid_file']);
        /**
         * 重启进程
         */
        \Swoole\Process::kill($pid,SIGUSR1);
    }

    /**
     * 重启服务
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function restart()
    {
        $this -> stop();
        $this -> start();
    }

    /**
     * 执行
     * @return mixed|void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function execute()
    {
        /**
         * 执行命令
         */
        if(!(isset($this -> command[0]) && strlen($this -> command[0]) > 0)){
            return $this -> help();
        }
        /**
         * 选择执行命令
         */
        switch ($this -> command[0]){
            case 'start':
                return $this -> start();
                break;
            case 'stop':
                return $this -> stop();
                break;
            case 'reload':
                return $this -> reload();
                break;
            case 'restart':
                return $this -> restart();
                break;
            default:
                return $this -> help();
                break;
        }
    }
}