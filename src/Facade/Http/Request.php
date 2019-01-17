<?php
namespace Itxiao6\Framework\Facade\Http;

use Itxiao6\Framework\Framework;

/**
 * Http 请求基类
 * Class Request
 * @package Itxiao6\Framework\Facade\Http
 */
abstract class Request
{
    /**
     * 请求头部信息
     * @var null | array
     */
    protected $headers = null;
    /**
     * 请求的cookie
     * @var null | array
     */
    protected $cookies = null;
    /**
     * POST 参数
     * @var null | array
     */
    protected $post = null;
    /**
     * GET 参数
     * @var null |array
     */
    protected $get = null;
    /**
     * 请求方法
     * @var null | string
     */
    protected $request_method = null;
    /**
     * @var null |mixed
     */
    protected $request = null;
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
     * @return mixed|void
     */
    public function getGetParam()
    {
        if(!($this -> get instanceof GET)){
            $this -> get = Framework::getContainerInterface() -> make(GET::class) -> boot(is_array($this -> getRawRequest() -> get)?$this -> getRawRequest() -> get:[]);
        }
        return $this -> get;
    }

    /**
     * 获取POST 参数
     * @return POST|mixed|null
     */
    public function getPostParam()
    {
        if(!($this -> post instanceof POST)){
            $this -> post = Framework::getContainerInterface() -> make(POST::class) -> boot(is_array($this -> request -> getRawRequest() -> post)?$this -> getRawRequest() -> post:[]);
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