<?php
namespace Itxiao6\Framework\Facade\Http\Abstracts;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Http\Headers;
use Itxiao6\Framework\Facade\Http\Request;
use Itxiao6\Framework\Facade\Http\Response;
use Itxiao6\Framework\Facade\Http\Status;

/**
 * 控制器基础类
 * Class Controller
 * @package Itxiao6\Framework\Facade\Http\Abstracts
 */
abstract class Controller
{
    /**
     * @var null | Request
     */
    protected $request = null;
    /**
     * @var null | Response
     */
    protected $response = null;
    /**
     * @var null | Container
     */
    protected $container = null;
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
     * 响应头信息
     * @var array
     */
    protected $responseHeaders = [];

    /**
     * 初始化
     * Controller constructor.
     */
    public function __construct()
    {
        $this -> responseHeaders = (new Headers()) -> boot([]);
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
        $this -> response -> writeContent('找不到操作:'.$this -> actionName);
    }

    /**
     * 异常处理
     * @param \Throwable $throwable
     */
    protected function __onException(\Throwable $throwable)
    {
        $this -> response -> writeContent('异常:'.$throwable -> getMessage());
    }

    /**
     * 注入容器
     * @param Container $container
     */
    public function __container(Container $container)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        $this -> container = $container;
        return $this;
    }

    /**
     * 注入请求
     * @param Request $request
     * @return $this
     */
    public function __request(Request $request)
    {
        $this -> request = $request;
        return $this;
    }

    /**
     * 注入响应
     * @param Response $response
     * @return $this
     */
    public function __response(Response $response)
    {
        $this -> response = $response;
        return $this;
    }

    /**
     * 回收机制
     */
    protected function __gc()
    {
        /**
         * 响应头设置
         */
        if($this -> responseHeaders instanceof Headers){
            $this -> response -> withHeader($this -> responseHeaders);
        }
        /**
         * 结束请求
         */
        $this -> response -> endResponse();
        /**
         * 恢复默认值
         */
        foreach ($this->defaultProperties as $property => $value){
            $this->{$property} = $value;
        }
    }

    /**
     * 析构方法
     */
    public function __destruct()
    {
        $this -> __gc();
    }
    /**
     * 返回JSON数据
     * @param $data
     * @param bool $is_end
     */
    protected function ReturnJosn($data,$is_end = false)
    {
        /**
         * 判断请求是否已经结束
         */
        if($this -> response -> ResponseIsEnd()){return ;}
        /**
         * 响应数据
         */
        $this->response->writeContent(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        /**
         * 内容类型
         */
        $this -> responseHeaders -> addHeader('Content-Type','application/json;charset=utf-8');
        /**
         * 允许跨域访问的来源域名
         */
        $this -> responseHeaders -> addHeader('Access-Control-Allow-Origin','*');
        /**
         * 允许跨域的方法
         */
        $this -> responseHeaders -> addHeader('Access-Control-Allow-Method','POST');
        /**
         * 允许修改的协议头
         */
        $this -> responseHeaders -> addHeader('Access-Control-Allow-Headers','accept, content-type');
        /**
         * 响应码
         */
        $this->response->withStatus(Status::CODE_OK);
        /**
         * 判断是否要结束请求
         */
        if($is_end){$this -> response -> endResponse();}
    }
}