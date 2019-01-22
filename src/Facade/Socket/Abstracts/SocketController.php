<?php
namespace CloverSwoole\CloverSwoole\Facade\Socket\Abstracts;

use CloverSwoole\CloverSwoole\Facade\Route\RouteInterface;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SocketFrame;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SocketServer;

/**
 * Socket Controller
 * @package CloverSwoole\CloverSwoole\Facade\Socket\Abstracts
 */
abstract class SocketController
{
    /**
     * @var RouteInterface|null
     */
    protected $route = null;
    /**
     * @var null|arry
     */
    protected $defaultProperties = null;
    /**
     * 操作名
     * @var string
     */
    protected $actionName = 'index';
    /**
     * @var null| SocketServer
     */
    protected $server = null;
    /**
     * @var null | SocketFrame
     */
    protected $frame = null;
    /**
     * 初始化
     * Controller constructor.
     */
    public function __construct(SocketServer $server,SocketFrame $frame)
    {
        $this -> server = $server;
        $this -> frame = $frame;
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
     * 返回JSON数据
     * @param $data
     * @param bool $is_end
     */
    protected function returnJosn($data,$is_end = false)
    {
        /**
         * 响应数据
         */
        $this->server->push($this -> frame -> fd,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    }
    protected function __actionNotFound($actionName)
    {

    }
    protected function __onException($throwable)
    {

    }
}