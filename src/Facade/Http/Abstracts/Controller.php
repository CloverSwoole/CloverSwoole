<?php
namespace Itxiao6\Framework\Facade\Http\Abstracts;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Http\HeaderItem;
use Itxiao6\Framework\Facade\Http\Headers;
use Itxiao6\Framework\Facade\Http\Request;
use Itxiao6\Framework\Facade\Http\Response;
use Itxiao6\Framework\Facade\Http\Status;
use Itxiao6\Framework\Facade\Route\Route;
use Itxiao6\Framework\Facade\Route\RouteInterface;

/**
 * 控制器基础类
 * Class Controller
 * @package Itxiao6\Framework\Facade\Http\Abstracts
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
     * @var array
     */
    protected $defaultProperties = [];
    /**
     * @var null | RouteInterface|Route
     */
    protected $route = null;
    /**
     * 初始化
     * Controller constructor.
     */
    public function __construct(RouteInterface $route)
    {
        $this -> route = $route;
        //支持在子类控制器中以private，protected来修饰某个方法不可见
        $list = [];
        $ref = new \ReflectionClass(static::class);
        $public = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($public as $item){
            array_push($list,$item->getName());
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
                '__clone','__construct','__call',
                '__callStatic','__get','__set',
                '__isset','__unset','__sleep',
                '__wakeup','__toString','__invoke',
                '__set_state','__clone','__debugInfo'
            ]
        );
        //获取，生成属性默认值
        $ref = new \ReflectionClass(static::class);
        $properties = $ref->getProperties();
        foreach ($properties as $property){
            //不重置静态变量
            if(($property->isPublic() || $property->isProtected()) && !$property->isStatic()){
                $name = $property->getName();
                $this->defaultProperties[$name] = $this->{$name};
            }
        }
    }
    /**
     * 系统钩子
     * @param $actionName
     */
    public function __hook($actionName)
    {
        $this -> actionName = $actionName;
        try{
            if(in_array($this -> actionName,$this->allowMethods)){
                $this->$actionName();
            }else{
                $this->__actionNotFound($actionName);
            }
        }catch (\Throwable $throwable){
            $this -> __onException($throwable);
        }
    }
    /**
     * 找不到指定的操作
     */
    protected function __actionNotFound()
    {
        $this -> __getResponse() -> writeContent('找不到操作:'.$this -> actionName);
    }
    /**
     * 返回JSON数据
     * @param $data
     * @param bool $is_end
     */
    protected function returnJosn($data,$is_end = false)
    {
        /**
         * 判断请求是否已经结束
         */
        if($this -> __getResponse() -> ResponseIsEnd()){return ;}
        /**
         * 内容类型
         */
        $this -> __getResponse() -> withHeader(new HeaderItem('Content-Type','application/json;charset=utf-8'));
        /**
         * 允许跨域访问的来源域名
         */
        $this -> __getResponse() -> withHeader(new HeaderItem('Access-Control-Allow-Origin','*'));
        /**
         * 允许跨域的方法
         */
        $this -> __getResponse() -> withHeader(new HeaderItem('Access-Control-Allow-Method','POST'));
        /**
         * 允许修改的协议头
         */
        $this -> __getResponse() -> withHeader(new HeaderItem('Access-Control-Allow-Headers','accept, content-type'));
        /**
         * 响应码
         */
        $this->__getResponse()->withStatus(Status::CODE_OK);
        /**
         * 响应数据
         */
        $this->__getResponse()->writeContent(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        /**
         * 判断是否要结束请求
         */
        if($is_end){$this -> __getResponse() -> endResponse();}
    }

    /**
     * 异常处理
     * @param \Throwable $throwable
     * @throws \Throwable
     */
    protected function __onException(\Throwable $throwable)
    {
        /**
         * 异常消息
         */
        $this -> __getResponse() -> writeContent('异常:'.$throwable -> getMessage()."<br />\n");
        /**
         * 抛出文件
         */
        $this -> __getResponse() -> writeContent('文件:'.$throwable -> getFile()."<br />\n");
        /**
         * 抛出位置
         */
        $this -> __getResponse() -> writeContent('位置:'.$throwable -> getLine()."<br />\n");
        /**
         * 结束请求
         */
        $this -> __getResponse() -> endResponse();
    }
    /**
     * 注入容器
     * @param Container $container
     */
    public function __getContainer()
    {
        return $this -> route -> getContainer();
    }
    /**
     * 获取请求实例
     * @return Request|null
     */
    public function __getRequest()
    {
        return $this -> route -> getRequest();
    }
    /**
     * 获取响应
     * @return Response|null
     */
    public function __getResponse()
    {
        return $this -> route -> getResponse();
    }
    /**
     * 析构方法
     */
    public function __destruct()
    {
        $this -> __gc();
    }
    /**
     * 回收机制
     */
    protected function __gc()
    {
        /**
         * 结束请求
         */
        $this -> __getResponse() -> endResponse();
        /**
         * 恢复默认值
         */
        foreach ($this->defaultProperties as $property => $value){
            $this->{$property} = $value;
        }
    }
}