<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use CloverSwoole\CloverSwoole\Facade\Http\Cookies;
use CloverSwoole\CloverSwoole\Facade\Http\GET;
use CloverSwoole\CloverSwoole\Facade\Http\HeaderItem;
use CloverSwoole\CloverSwoole\Facade\Http\Headers;
use CloverSwoole\CloverSwoole\Facade\Http\POST;
use CloverSwoole\CloverSwoole\Facade\Http\RequestCookies;
use CloverSwoole\CloverSwoole\Facade\Http\RequestHeaders;
use CloverSwoole\CloverSwoole\Framework;
/**
 * Swoole 请求具体类
 * Class Request
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class Request extends \CloverSwoole\CloverSwoole\Facade\Http\Request
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