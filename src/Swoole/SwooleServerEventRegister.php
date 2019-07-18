<?php
namespace CloverSwoole\Swoole;
use Swoole\Server;
use Closure;

/**
 * SwooleServer 注册数
 * Class SwooleServerEventRegister
 * @package CloverSwoole\Swoole
 */
class SwooleServerEventRegister
{
    const onStart = 'start';
    const onShutdown = 'shutdown';
    const onWorkerStart = 'workerStart';
    const onWorkerStop = 'workerStop';
    const onWorkerExit = 'workerExit';
    const onTimer = 'timer';
    const onConnect = 'connect';
    const onReceive = 'receive';
    const onPacket = 'packet';
    const onClose = 'close';
    const onBufferFull = 'bufferFull';
    const onBufferEmpty = 'bufferEmpty';
    const onTask = 'task';
    const onFinish = 'finish';
    const onPipeMessage = 'pipeMessage';
    const onWorkerError = 'workerError';
    const onManagerStart = 'managerStart';
    const onManagerStop = 'managerStop';
    const onRequest = 'request';
    const onHandShake = 'handShake';
    const onMessage = 'message';
    const onOpen = 'open';
    /**
     * 事件回调设置
     * @var array
     */
    protected $event_callback = [];
    /**
     * 注入实例
     * @var null | static
     */
    protected static $interface = null;

    /**
     * 获取实例
     * @return SwooleServerEventRegister|null
     */
    public static function getInterface()
    {
        if(self::$interface == null){
            return new static();
        }
        return self::$interface;
    }

    /**
     * 设置全局可访问
     * @return $this
     */
    public function setGlobal()
    {
        self::$interface = $this;
        return $this;
    }

    /**
     * SwooleServer事件模型构造器
     * SwooleServerEventRegister constructor.
     */
    protected function __construct()
    {
        /**
         * 默认onRequest 处理
         */
        $this -> addEvent(self::onRequest,function(\Swoole\Http\Request $request,\Swoole\Http\Response $response){
            /**
             * 放置全局请求实例
             */
            (new Request($request)) -> setAsGlobal(true);
            /**
             * 放置全局响应实例
             */
            (new Response($response)) -> setAsGlobal(true);
            try{
                /**
                 * 路由初始化
                 */
                \CloverSwoole\Route\Route::getInterface();
            }catch (\Throwable $throwable){
                /**
                 * 捕获路由异常 TODO
                 */
                $response -> write("路由异常:".$throwable -> getMessage());
                $response -> end();
            }
        });
    }

    /**
     * 绑定已经设置的回调
     * @param Server $server
     */
    public function binding(Server $server)
    {
        foreach ($this ->event_callback as $event_name=>$callback){
            $server -> on($event_name,$callback);
        }
    }

    /**
     * 新增监听事件
     * @param $event_name
     * @param $callback
     * @return $this
     */
    public function addEvent($event_name,$callback)
    {
        $this -> event_callback[$event_name] = $callback;
        return $this;
    }
}