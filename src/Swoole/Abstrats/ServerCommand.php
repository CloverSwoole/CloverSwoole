<?php

namespace CloverSwoole\Swoole\Abstrats;


use CloverSwoole\Swoole\SwooleServerConfig;
use CloverSwoole\Swoole\SwooleServerEventRegister;
use Swoole\Server;

abstract class ServerCommand extends \CloverSwoole\Command\Abstracts\Command
{
    /**
     * Server Config
     * @var array
     */
    protected $config = [];
    /**
     *
     * @var null | Server
     */
    protected $server = null;

    /**
     * 命令配置
     */
    protected function configure()
    {
        /**
         * 设置命令
         */
        $this->setName('swoole_server');
    }

    /**
     * 命令帮助
     */
    protected function help()
    {
        if (!(isset($this->command[1]) && strlen($this->command[1]) > 0)) {
            echo "start:启动服务\nstop:停止服务\nreload:重载服务\nrestart:重启服务\nhelp:服务帮助\n";
            return;
        }
        switch ($this->command[1]) {
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
     */
    public function __construct(string $name = '')
    {
        parent::__construct($name);
    }

    /**
     * 绑定事件回调
     */
    protected function binding()
    {
        SwooleServerEventRegister::getInterface()->binding($this->server);
    }

    /**
     * 创建server
     */
    protected function create_server()
    {
        /**
         * 实例化server
         */
        $this->server = new Server($this->config['host'], $this->config['port']);
    }

    /**
     * 设置Server参数
     */
    protected function setServeConfig()
    {
        /**
         * 判断参数是否存在
         */
        if (is_array($this->config['server']) && count($this->config['server']) > 0) {
            /**
             * 获取http server 配置项
             */
            $server_config = $this->config['server'];
            /**
             * 判断是否要以守护进程运行
             */
            if (count($this->options) && isset($this->options['d'])) {
                $server_config['daemonize'] = true;
            }
            /**
             * 判断是否要自定义pid进程号存储id
             */
            if (isset($this->options['pid']) && file_exists($this->options['pid'])) {
                $server_config['pid_file'] = $this->options['pid'];
            }
            /**
             * 设置参数
             */
            $this->server->set($server_config);
        }
    }

    /**
     * 启动服务
     */
    protected function start()
    {
        /**
         * 创建Server
         */
        $this->create_server();
        /**
         * 处理事件绑定
         */
        $this->binding();
        /**
         * 设置Server参数
         */
        $this->setServeConfig();
        /**
         * 启动服务
         */
        $this->server->start();
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
        if (isset($this->options['pid']) && file_exists($this->options['pid'])) {
            $this->config['server']['pid_file'] = $this->options['pid'];
        }
        if ((!isset($this->config['server']['pid_file'])) || (!file_exists(!isset($this->config['server']['pid_file'])))) {
            echo "pid files no exists\n";
            return;
        }
        $pid = file_get_contents($this->config['server']['pid_file']);
        if (!\Swoole\Process::kill($pid, 0)) {
            echo "PID :{$pid} not exist \n";
            return false;
        }
        /**
         * 判断是否为强制杀掉
         */
        if (in_array('-f', $this->options)) {
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
        if (isset($this->options['pid']) && file_exists($this->options['pid'])) {
            $this->config['server']['pid_file'] = $this->options['pid'];
        }
        if (!file_exists($this->config['server']['pid_file'])) {
            echo "pid files no exists\n";
            return;
        }
        /**
         * 获取pid
         */
        $pid = file_get_contents($this->config['server']['pid_file']);
        /**
         * 重启进程
         */
        \Swoole\Process::kill($pid, SIGUSR1);
    }

    /**
     * 重启服务
     */
    protected function restart()
    {
        $this->stop();
        $this->start();
    }

    /**
     * 执行
     * @return bool|mixed|void
     */
    protected function execute()
    {
        /**
         * 初始化配置
         */
        $this->config = SwooleServerConfig::getInterface()->getConfig();
        /**
         * 判断是否自定义端口
         */
        if (isset($this->options['p']) && $this->options['p'] > 0) {
            $this->config['port'] = $this->options['p'];
        }
        /**
         * 判断是否需要自定义监听ip
         */
        if (isset($this->options['h']) && $this->options['h'] > 0) {
            $this->config['host'] = $this->options['h'];
        }
        /**
         * 执行命令
         */
        if (!(isset($this->command[0]) && strlen($this->command[0]) > 0)) {
            return $this->help();
        }
        /**
         * 选择执行命令
         */
        switch ($this->command[0]) {
            case 'start':
                return $this->start();
                break;
            case 'stop':
                return $this->stop();
                break;
            case 'reload':
                return $this->reload();
                break;
            case 'restart':
                return $this->restart();
                break;
            default:
                return $this->help();
                break;
        }
    }
}