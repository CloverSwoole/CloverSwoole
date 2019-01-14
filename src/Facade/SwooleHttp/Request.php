<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Http\Cookies;
use Itxiao6\Framework\Facade\Http\GET;
use Itxiao6\Framework\Facade\Http\HeaderItem;
use Itxiao6\Framework\Facade\Http\Headers;
use Itxiao6\Framework\Facade\Http\POST;
use Itxiao6\Framework\Facade\Http\RequestCookies;
use Itxiao6\Framework\Facade\Http\RequestHeaders;
use Itxiao6\Framework\Framework;
/**
 * Swoole 请求具体类
 * Class Request
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class Request extends \Itxiao6\Framework\Facade\Http\Request
{
    /**
     * 创建一个请求实例
     * @param $request
     * @return $this
     */
    public function boot($request)
    {
        $this -> request = $request;
        return $this;
    }
    /**
     * 获取请求头
     * @return Headers|mixed|null
     */
    public function getHeaders()
    {
        if(!($this -> headers instanceof Headers)){
            $this -> headers = Framework::getContainerInterface() -> make(RequestHeaders::class) -> boot(is_array($this -> request -> header)?$this -> request -> header:[]);
        }
        return $this -> headers;
    }

    /**
     * 获取请求的Cookie
     * @return Cookies|mixed|null
     */
    public function getCookie()
    {
        if(!($this -> cookies instanceof Cookies)){
            $this -> cookies = Framework::getContainerInterface() -> make(RequestCookies::class) -> boot(is_array($this -> request -> cookie)?$this -> request -> cookie:[]);
        }
        return $this -> cookies;
    }
    /**
     * 获取请求方法
     * @return mixed|void
     */
    public function getRequestMethod()
    {
        if(strlen($this -> request_method) < 1){
            $this -> request_method = $this -> request -> server['request_method'];
        }
        return $this -> request_method;
    }
    /**
     * 获取原生请求
     * @return mixed|void|\swoole_http_request
     */
    public function getRawRequest()
    {
        return $this -> request;
    }
    /**
     * 获取请求的路径
     * @return mixed
     */
    public function getUri()
    {
        return isset($this -> request -> server['request_uri'])?$this -> request -> server['request_uri']:null;
    }

    public function getClientAddr()
    {
        // TODO: Implement getClientAddr() method.
    }
    public function getClientPort()
    {
        // TODO: Implement getClientPort() method.
    }
    public function getServerPort()
    {
        // TODO: Implement getServerPort() method.
    }
}