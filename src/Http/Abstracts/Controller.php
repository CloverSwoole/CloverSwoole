<?php

namespace CloverSwoole\Http\Abstracts;

use CloverSwoole\Exception\ExceptionHandler;
use CloverSwoole\Http\Request;
use CloverSwoole\Http\Response;
use CloverSwoole\Session\SessionManager;
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
     * @var array|null
     */
    protected $defaultProperties = null;

    /**
     * session 实例
     * @var array
     */
    private $session_interface = [];

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
         * 支持在子类控制器中以private，protected来修饰某个方法不可见
         */
        $list = [];
        $ref = new ReflectionClass(static::class);
        $public = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($public as $item) {
            array_push($list, $item->getName());
        }
        $this->allowMethods = array_diff($list,
            [
                '__hook',
                '__actionNotFound',
                '__container',
                '__request',
                '__response',
                '__gc',
                '__destruct',
                '__clone', '__construct', '__call',
                '__callStatic', '__get', '__set',
                '__isset', '__unset', '__sleep',
                '__wakeup', '__toString', '__invoke',
                '__set_state', '__clone', '__debugInfo'
            ]
        );
        /**
         * 开启缓存区
         */
        ob_start();
        /**
         * 获取，生成属性默认值
         */
        $ref = new ReflectionClass(static::class);
        $properties = $ref->getProperties();
        foreach ($properties as $property) {
            /**
             * 不重置静态变量
             */
            if (($property->isPublic() || $property->isProtected()) && !$property->isStatic()) {
                $name = $property->getName();
                $this->defaultProperties[$name] = $this->{$name};
            }
        }
        $this->__hook($actionName);
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
     * 系统钩子
     * @param $actionName
     * @throws \Throwable
     */
    public function __hook($actionName)
    {
        /**
         * 记录操作名称
         */
        $this->actionName = $actionName;
        try {
            /**
             * 判断操作是否被禁止访问
             */
            if (in_array($this->actionName, $this->allowMethods)) {
                /**
                 * 调用操作
                 */
                $this->{$actionName}();
            } else {
                /**
                 * 404
                 */
                $this->__actionNotFound($actionName);
            }
        } catch (\Throwable $throwable) {
            /**
             * 异常处理
             */
            $this->__onException($throwable);
        }
    }

    /**
     * 找不到指定的操作
     * @throws \Exception
     */
    protected function __actionNotFound()
    {
        throw new \Exception('找不到操作:' . $this->actionName);
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
    public function session($session_id = null)
    {
        if (!($this->session_interface instanceof SessionManager)) {
            $this->session_interface = SessionManager::getInterface($session_id)->start();
        }
        return $this->session_interface;
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