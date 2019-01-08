<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;

use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Http\Cookies;
use Itxiao6\Framework\Facade\Http\GET;
use Itxiao6\Framework\Facade\Http\Headers;
use Itxiao6\Framework\Facade\Http\POST;
use Itxiao6\Framework\Framework;

/**
 * Class Request
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class Request extends \Itxiao6\Framework\Facade\Http\Request
{

    protected $headers = null;
    protected $cookies = null;
    protected $post = null;
    protected $get = null;
    protected $request_method = null;
    /**
     * @var null |mixed
     */
    protected $request = null;

    /**
     *
     * Request constructor.
     */
    public function __construct()
    {

    }
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
            $this -> headers = Framework::getContainerInterface() -> make(Headers::class) -> boot(is_array($this -> request -> header)?$this -> request -> header:[]);
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
            $this -> cookies = Framework::getContainerInterface() -> make(Cookies::class) -> boot(is_array($this -> request -> cookie)?$this -> request -> cookie:[]);
        }
        return $this -> cookies;
    }

    /**
     * 获取GET 参数
     * @return mixed|void
     */
    public function getGETParam()
    {
        if(!($this -> get instanceof GET)){
            $this -> get = Framework::getContainerInterface() -> make(GET::class) -> boot(is_array($this -> request  -> get)?$this -> request -> get:[]);
        }
        return $this -> get;
    }

    /**
     * 获取POST 参数
     * @return POST|mixed|null
     */
    public function getPOSTParam()
    {
        if(!($this -> post instanceof POST)){
            $this -> post = Framework::getContainerInterface() -> make(POST::class) -> boot(is_array($this -> request -> request -> post)?$this -> request -> post:[]);
        }
        return $this -> post;
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
     * @return mixed|void
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
//Swoole\Http\Request {#22
//    +fd: 1
//    +header: array:7 [
//        "host" => "127.0.0.1:5200"
//    "connection" => "keep-alive"
//    "upgrade-insecure-requests" => "1"
//    "user-agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36"
//    "accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8"
//    "accept-encoding" => "gzip, deflate, br"
//    "accept-language" => "zh-CN,zh;q=0.9,zh-TW;q=0.8,en;q=0.7"
//  ]
//  +server: array:12 [
//        "query_string" => "kjhsakjhd"
//    "request_method" => "GET"
//    "request_uri" => "/sahsad/sadasdshkh/sad.html"
//    "path_info" => "/sahsad/sadasdshkh/sad.html"
//    "request_time" => 1546826017
//    "request_time_float" => 1546826017.7488
//    "server_port" => 5200
//    "remote_port" => 62818
//    "remote_addr" => "127.0.0.1"
//    "master_time" => 1546826017
//    "server_protocol" => "HTTP/1.1"
//    "server_software" => "swoole-http-server"
//  ]
//  +request: null
//    +cookie: array:3 [
//        "UM_distinctid" => "16652af814d4b6-0d0651b0af8ccf-346c780e-1fa400-16652af814ef3"
//    "CNZZDATA1273259362" => "411069392-1538984544-null|1538981715"
//    "_ga" => "GA1.1.1724910860.1538987506"
//  ]
//  +get: array:1 [
//        "kjhsakjhd" => ""
//    ]
//    +files: null
//    +post: null
//    +tmpfiles: null
//}
