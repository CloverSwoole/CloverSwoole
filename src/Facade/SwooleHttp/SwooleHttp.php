<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Command\Command;
use Itxiao6\Framework\Facade\SwooleHttp\EasySwoole\WebService;

/**
 * Swoole Http 组件
 * Class SwooleHttp
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class SwooleHttp implements SwooleHttpInterface
{
    /**
     * 应用容器
     * @var null|Container
     */
    protected $container = null;
    /**
     * Swoole 组件
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
//        $res = new \Swoole\Process(function()use($container){

//        });
//        dump($res -> start());die();
    }

    /**
     * 启动服务
     * @param $options
     */
    protected function start($options)
    {
        /**
         * 获取server
         */
        $http = new \Swoole\Http\Server($this->container['config']['swoole_http']['host'], $this -> container['config']['swoole_http']['port']);
        /**
         * 判断参数是否存在
         */
        if(is_array($this -> container['config']['swoole_http']['server']) && count($this -> container['config']['swoole_http']['server']) > 0){
            /**
             * 设置参数
             */
            $http->set($this -> container['config']['swoole_http']['server']);
        }
        $container = $this -> container;
        /**
         * 监听启动事件
         */
        $http->on("start", function ($server) use($container){
            echo "Swoole http server is started at http://127.0.0.1:{$container['config']['swoole_socket']['port']}\n";
        });
        /**
         * 实例化WebServer
         */
        $service = new WebService('App\\Http\\Controller\\',5,100,$this -> container);
        /**
         * 设置异常处理程序
         */
        $service->setExceptionHandler(function (\Throwable $throwable,\EasySwoole\Http\Request $request,\EasySwoole\Http\Response $response){
            $response->write('msg:'.$throwable->getMessage().'file:'.$throwable->getFile().'line:'.$throwable->getLine());
        });
        /**
         * 监听请求到达 事件
         */
        $http->on("request", function ($request, $response)use($service) {
            $req = new \EasySwoole\Http\Request($request);
            $service->onRequest($req,new \EasySwoole\Http\Response($response));
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
        if((!isset($this -> container['config']['swoole_http']['server']['pid_file'])) || (!file_exists($this -> container['config']['swoole_http']['server']['pid_file']))){
            echo "pid files no exists\n";
            return ;
        }
        $pid = file_get_contents($this -> container['config']['swoole_http']['server']['pid_file']);
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
        if((!isset($this -> container['config']['swoole_http']['server']['pid_file'])) || (!file_exists($this -> container['config']['swoole_http']['server']['pid_file']))){
            echo "pid files no exists\n";
            return ;
        }
        $pid = file_get_contents($this -> container['config']['swoole_http']['server']['pid_file']);
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