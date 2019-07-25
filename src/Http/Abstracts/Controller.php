<?php

namespace CloverSwoole\Http\Abstracts;

use CloverSwoole\Exception\ExceptionHandler;
use CloverSwoole\Http\Request;
use CloverSwoole\Http\Response;
use CloverSwoole\Session\SessionManager;
use CloverSwoole\View\ViewManager;
use ReflectionClass;
use ReflectionMethod;

/**
 * 控制器基础类
 * Class Controller
 * @package CloverSwoole\Http\Abstracts
 */
abstract class Controller
{
    /**
     * @var null | string
     */
    protected $actionName = null;
    /**
     * @var array
     */
    protected $allowMethods = [];
    /**
     * 禁止访问的
     * @var array
     */
    protected $prohibitMethods = [
        '__hook',
        '__actionNotFound',
        '__container',
        '__request',
        '__response',
        '__gc',
        '__destruct',
        '__clone',
        '__construct',
        '__call',
        '__callStatic',
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__sleep',
        '__wakeup',
        '__toString',
        '__invoke',
        '__set_state',
        '__clone',
        '__debugInfo'
    ];
    /**
     * @var array|null
     */
    protected $defaultProperties = null;
    /**
     * session 实例
     * @var array
     */
    private $session_interface = [];
    /**
     * 视图实例
     * @var null | ViewManager
     */
    private $view_manager = null;
    /**
     * 视图数据
     * @var array
     */
    private $view_data = [];

    /**
     * 初始化
     * Controller constructor.
     * @param $actionName
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function __construct($actionName)
    {
        /**
         * 定义公开的方法
         */
        $publicMethods = [];
        /**
         * 映射当前控制器
         */
        $ref = new ReflectionClass(static::class);
        /**
         * 获取公开的方法
         */
        $publicList = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
        /**
         * 循环置入
         */
        foreach ($publicList as $item) {
            array_push($publicMethods, $item->getName());
        }
        /**
         * 过滤掉禁止访问的方法
         */
        $this->allowMethods = array_diff($publicMethods, $this->prohibitMethods);
        /**
         * 获取属性
         */
        $properties = $ref->getProperties();
        /**
         * 循环处理默认属性
         */
        foreach ($properties as $property) {
            /**
             * 不重置静态变量
             */
            if (($property->isPublic() || $property->isProtected()) && !$property->isStatic()) {
                $name = $property->getName();
                /**
                 * 记录默认值
                 */
                $this->defaultProperties[$name] = $this->{$name};
            }
        }
        /**
         * 开启缓存区
         */
        ob_start();
        /**
         * 判断访问的方法是否合法
         */
        if (in_array($actionName, $this->allowMethods)) {
            /**
             * 处理前置操作
             */
            if (method_exists($this, 'actionBefore')) {
                /**
                 * 执行前置操作
                 */
                $this->actionBefore($actionName, function () use ($actionName) {
                    /**
                     * 执行操作
                     */
                    $this->{$actionName}(Request::getInterface());
                });
            } else {
                /**
                 * 执行操作
                 */
                $this->{$actionName}(Request::getInterface());
            }
        } else {
            $this->__actionNotFound($actionName);
        }
    }

    /**
     * 前置操作拦截
     * @param $actionName
     * @param $next
     */
    protected function actionBefore($actionName, $next)
    {
        $next();
    }

    /**
     * 获取请求的参数
     * @param null | string $key
     * @return array|mixed|null
     */
    public function getRequestParam($key = null)
    {
        if ($key === null) {
            return array_merge(is_array(Request::getInterface()->getPostParam()) ? Request::getInterface()->getPostParam() : [], is_array(Request::getInterface()->getGetParam()) ? Request::getInterface()->getGetParam() : []);
        }
        $res = Request::getInterface()->getPostParam($key);
        if ($res != null) {
            return $res;
        }
        return Request::getInterface()->getGetParam($key);
    }

    /**
     * 找不到指定的操作
     * @param $actionName
     * @throws \Exception
     */
    protected function __actionNotFound($actionName)
    {
        throw new \Exception('找不到操作:' . $actionName);
    }

    /**
     * 返回JSON数据
     * @param $data
     * @param bool $is_end
     */
    protected function returnJson($data, $is_end = false)
    {
        /**
         * 判断请求是否已经结束
         */
        if (Response::getInterface()->ResponseIsEnd()) {
            return;
        }
        /**
         * 内容类型
         */
        Response::getInterface()->withHeader('Content-Type', 'application/json;charset=utf-8');
        /**
         * 允许跨域访问的来源域名
         */
        Response::getInterface()->withHeader('Access-Control-Allow-Origin', Request::getInterface()->getOriginLocation());
        /**
         * 允许跨域的方法
         */
        Response::getInterface()->withHeader('Access-Control-Allow-Method', 'POST');
        /**
         * 允许修改的协议头
         */
        Response::getInterface()->withHeader('Access-Control-Allow-Headers', 'accept, content-type');
        /**
         * 响应码
         */
        Response::getInterface()->withStatus(200);
        /**
         * 响应数据
         */
        Response::getInterface()->withContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        /**
         * 判断是否要结束请求
         */
        if ($is_end) {
            Response::getInterface()->endResponse();
        }
    }

    /**
     * 获取会话实例
     * @param null $session_id
     * @return SessionManager
     * @throws \Exception
     */
    protected function session($session_id = null)
    {
        if (!($this->session_interface instanceof SessionManager)) {
            $this->session_interface = SessionManager::getInterface($session_id)->start();
        }
        return $this->session_interface;
    }

    /**
     * 获取View 实例
     * @return ViewManager|null
     */
    protected function view()
    {
        if (!($this->view_manager != null && $this->view_manager instanceof ViewManager)) {
            $this->view_manager = (new ViewManager());
        }
        return $this->view_manager;
    }

    /**
     * 渲染并且显示view
     * @param string $name
     * @param array $data
     * @return Response|null
     */
    protected function display(string $name, $data = [])
    {
        /**
         * 设置协议头
         */
        Response::getInterface()->withHeader('Content-Type', 'text/html;charset=utf-8');
        /**
         * 渲染并且显示模板
         */
        return Response::getInterface()->withContent($this->view()->reader($name, array_merge($this->view_data, $data)));
    }

    /**
     * 放置变量到模板引擎
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function assign(string $name, $value)
    {
        $this->view_data[$name] = $value;
        return $this;
    }

    /**
     * 异常处理
     * @param \Throwable $throwable
     * @throws \Throwable
     */
    protected function __onException(\Throwable $throwable)
    {
        ExceptionHandler::getInterface()->catchController($throwable);
    }

    /**
     * 析构方法
     */
    public function __destruct()
    {
        $this->__gc();
    }

    /**
     * 回收机制
     */
    protected function __gc()
    {
        /**
         * 结束请求
         */
        Response::getInterface()->endResponse();
        /**
         * 恢复默认值
         */
        if (is_array($this->defaultProperties)) {
            foreach ($this->defaultProperties as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }
}