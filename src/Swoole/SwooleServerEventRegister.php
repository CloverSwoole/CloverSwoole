<?php

namespace CloverSwoole\Swoole;

use CloverSwoole\Exception\ExceptionHandler;
use CloverSwoole\Route\Socket;
use CloverSwoole\Swoole\Task\AbstractAsyncTask;
use CloverSwoole\Swoole\Task\QuickTaskInterface;
use CloverSwoole\Swoole\Task\SuperClosure;
use CloverSwoole\Swoole\Task\TaskManager;
use Swoole\Server;

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
        if (self::$interface == null) {
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
        $this->addEvent(self::onRequest, function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
            /**
             * 放置全局请求实例
             */
            (new Request($request))->setAsGlobal(true);
            /**
             * 放置全局响应实例
             */
            (new Response($response))->setAsGlobal(true);
            try {
                /**
                 * 路由初始化
                 */
                \CloverSwoole\Route\Route::getInterface();
            } catch (\Throwable $throwable) {
                /**
                 * 捕获路由异常
                 */
                ExceptionHandler::getInterface() -> catchRoute($throwable);
            }
        });
        /**
         * 默认处理Socket连接
         */
        $this->addEvent(self::onOpen, function (\Swoole\WebSocket\Server $server, \Swoole\Http\Request $request) {
            /**
             * 放置Server
             */
            (new ServerManager($server))->setAsGlobal(true);
            /**
             * 存储连接
             */
            Socket::onOpen($server, $request);
        });
        /**
         * 任务投递处理
         */
        $this->addEvent(self::onTask, function ($server, \Swoole\Server\Task $task) {
            $finishData = null;
            $taskObj = $task->data;
            if (is_string($taskObj) && class_exists($taskObj)) {
                $ref = new \ReflectionClass($taskObj);
                if ($ref->implementsInterface(QuickTaskInterface::class)) {
                    try {
                        $finishData = $taskObj::run($server, $task->id, $task->worker_id, $task->flags);
                    } catch (\Throwable $throwable) {
                        ExceptionHandler::getInterface()->catchTask($throwable);
                    }
                    goto finish;
                } else if ($ref->isSubclassOf(AbstractAsyncTask::class)) {
                    $taskObj = new $taskObj;
                }
            }
            if ($taskObj instanceof AbstractAsyncTask) {
                try {
                    $ret = $taskObj->__onTaskHook($task->id, $task->worker_id, $task->flags);
                    $finishData = $taskObj->__onFinishHook($ret, $task->id);
                } catch (\Throwable $throwable) {
                    ExceptionHandler::getInterface()->catchTask($throwable);
                }
            } else if ($taskObj instanceof SuperClosure) {
                try {
                    $finishData = $taskObj($server, $task->id, $task->worker_id, $task->flags);
                } catch (\Throwable $throwable) {
                    ExceptionHandler::getInterface()->catchTask($throwable);
                }
            } else if (is_callable($taskObj)) {
                try {
                    $finishData = call_user_func($taskObj, $server, $task->id, $task->worker_id, $task->flags);
                } catch (\Throwable $throwable) {
                    ExceptionHandler::getInterface()->catchTask($throwable);
                }
            }
            finish :{
                //禁止 process执行回调
                if (($server->setting['worker_num'] + $server->setting['task_worker_num']) > $task->worker_id) {
                    $task->finish($finishData);
                }
            }
        });
        /**
         * TaskFinish
         */
        $this -> addEvent(self::onFinish,function(Server $serv, int $task_id,$data){
            return $data;
        });
        /**
         * 默认处理Socket消息到达
         */
        $this->addEvent(self::onMessage, function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
            /**
             * 放置Server
             */
            (new ServerManager($server))->setAsGlobal(true);
            /**
             * 路由处理
             */
            Socket::onMessage($server, $frame);
        });
        /**
         * 定义默认的onWorkerStart
         */
        $this->addEvent(self::onWorkerStart, function (\Swoole\Server $server, int $workerId) {
            if ($workerId == 0) {
                /**
                 * 放置server
                 */
                ServerManager::getInterface($server)->setAsGlobal();
                /**
                 * 循环注册静态定时器
                 */
                foreach (TimerRegisterTree::getInterface()->getTimerItem() as $item) {
                    Timer::getInstance()->loop($item['ms'], function () use ($item) {
                        /**
                         * 投递异步任务
                         */
                        TaskManager::async(function () use ($item) {
                            try {
                                /**
                                 * 执行任务
                                 */
                                $item['callback']();
                            } catch (\Throwable $throwable) {
                                /**
                                 * 异常处理
                                 */
                                ExceptionHandler::getInterface()->catchTimer($throwable);
                            }
                        }, $item['success_callback']);
                    });
                }
            }
        });
    }

    /**
     * 绑定已经设置的回调
     * @param Server $server
     */
    public function binding(Server $server)
    {
        foreach ($this->event_callback as $event_name => $callback) {
            $server->on($event_name, $callback);
        }
    }

    /**
     * 新增监听事件
     * @param $event_name
     * @param $callback
     * @return $this
     */
    public function addEvent($event_name, $callback)
    {
        $this->event_callback[$event_name] = $callback;
        return $this;
    }
}