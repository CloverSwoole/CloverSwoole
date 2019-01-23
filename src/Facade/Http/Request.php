<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;

use CloverSwoole\CloverSwoole\Framework;

/**
 * Http 请求基类
 * Class Request
 * @package CloverSwoole\CloverSwoole\Facade\Http
 */
abstract class Request
{
    /**
     * 请求头部信息
     * @var null | array
     */
    protected $headers = [];
    /**
     * 请求的cookie
     * @var null | array
     */
    protected $cookies = [];
    /**
     * POST 参数
     * @var null | array
     */
    protected $post = [];
    /**
     * GET 参数
     * @var null |array
     */
    protected $get = [];
    /**
     * 请求方法
     * @var null | string
     */
    protected $request_method = 'GET';
    /**
     * @var null |mixed|\swoole_http_request
     */
    protected $request = null;
    /**
     * @var null | Request
     */
    protected static $global_request = null;
    /**
     * 设置全局访问
     * @param $bool
     */
    public function setAsGlobal($bool = true)
    {
        if($bool){
            static::$global_request = $this;
        }else{
            static::$global_request = null;
        }
    }
    /**
     * 获取响应句柄
     * @return Request|null
     */
    public static function getInterface()
    {
        return static::$global_request;
    }
    /**
     * 获取原生请求
     * @return mixed|void|mixed
     */
    public function getRawRequest()
    {
        return $this -> request;
    }
    /**
     * 获取GET 参数
     * @return mixed|void|GET
     */
    public function getGetParam()
    {
        if(!($this -> get instanceof GET)){
            $this -> get = Framework::getContainerInterface() -> make(GET::class) -> boot(is_array($this -> getRawRequest() -> get)?$this -> getRawRequest() -> get:[]);
        }
        return $this -> get;
    }

    /**
     * XML To Array
     * @param $xml
     * @return mixed
     */
    protected function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
    /**
     * 获取POST 参数
     * @return POST|mixed|null
     */
    public function getPostParam()
    {
        if(!($this -> post instanceof POST)){
            /**
             * 定义基础post 数据
             */
            if(is_array($this -> getRawRequest() -> post)){
                $postData = $this -> getRawRequest() -> post;
            }else{
                $postData = [];
            }
            /**
             * 根据协议头进行数据合并
             */
            if($this -> request -> header['content-type'] == 'application/json'){
                $postData = array_merge($postData,json_decode($this -> request -> rawcontent(),1));
            }else if($this -> request -> header['content-type'] == 'application/xml'){
                $postData = array_merge($postData,$this -> xmlToArray($this -> request -> rawcontent()));
            }
            $this -> post = Framework::getContainerInterface() -> make(POST::class) -> boot($postData);
        }
        return $this -> post;
    }
    /**
     * 获取请求头
     * @return mixed
     */
    abstract public function getHeaders();
    /**
     * 获取Cookie
     * @return mixed
     */
    abstract public function getCookie();
    /**
     * 获取请求方法
     * @return mixed
     */
    abstract public function getRequestMethod();

    /**
     * 获取请求服务端时的端口
     * @return mixed
     */
    abstract public function getServerPort();

    /**
     * 获取客户端请求时用的端口
     * @return mixed
     */
    abstract public function getClientPort();

    /**
     * 获取客户端的地址
     * @return mixed
     */
    abstract public function getClientAddr();

    /**
     * 获取完整路径
     * @return mixed
     */
    abstract public function getUri();

}