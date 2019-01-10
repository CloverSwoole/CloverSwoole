<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Itxiao6\Framework\Facade\Http\Cookies;
use Itxiao6\Framework\Facade\Http\Headers;
use Itxiao6\Framework\Facade\Http\Status;

/**
 * 响应
 * Class Response
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class Response extends \Itxiao6\Framework\Facade\Http\Response
{
    /**
     * @var null|\swoole_http_response
     */
    protected $response = null;
    /**
     * @var null | Headers
     */
    protected $headers = null;
    /**
     * @var null | Cookies
     */
    protected $cookies = null;
    /**
     * @var bool
     */
    protected $is_end = false;
    /**
     * 默认的Http 状态码
     * @var int
     */
    protected $http_code = Status::CODE_OK;

    /**
     * 构造方法
     * Response constructor.
     */
    public function __construct()
    {

    }

    /**
     * 启动组件
     * @param $response
     * @return $this
     */
    public function boot($response)
    {
        $this -> response = $response;
        return $this;
    }

    /**
     * 注入 Headers
     * @param Headers $headers
     * @return $this|mixed
     */
    public function withHeader(Headers $headers)
    {
        $this -> headers = $headers;
        return $this;
    }
    /**
     * 响应状态码
     * @param $code
     * @return $this|mixed
     */
    public function withStatus($code)
    {
        $this -> http_code = $code;
        return $this;
    }

    /**
     * 注入 Cookie
     * @param $cookie
     * @return mixed|void
     */
    public function withCookie(Cookies $cookies)
    {
        $this -> cookies = $cookies;
    }
    /**
     * 发送响应头部信息
     */
    protected function sendHeaders()
    {
        $headers = $this -> headers -> getHeaders();
        foreach ($headers as $key=>$item){
            if(is_array($item)){
                foreach ($item as $sub){
                    $this -> getRawResponse() -> header($key,$sub,false);
                }
            }else{
                $this -> getRawResponse() -> header($key,$item,false);
            }
        }
    }

    /**
     * 发送cookie
     */
    protected function sendCookie()
    {
        $cookies = $this -> cookies -> getCookies();
    }
    /**
     * 发送内容到客户端
     * @param $content
     * @return mixed|void
     */
    public function writeContent($content)
    {
        return $this -> response -> write(strval($content));
    }
    /**
     * 结束响应
     * @param string $content
     * @return mixed|void
     */
    public function endResponse($content = '')
    {
        /**
         * 判断响应是否已经结束
         */
        if($this -> is_end){
            return ;
        }
        /**
         * 判断结束前是否要 发送结尾信息
         */
        if(strlen($content) >= 1){
            $this -> writeContent($content);
        }
        /**
         * 发送响应头信息
         */
        if($this -> headers instanceof Headers){
            $this -> sendHeaders();
        }
        /**
         * 发送Cookie
         */
        if($this -> cookies instanceof Cookies){
            $this -> sendCookie();
        }
        /**
         * 标识响应已经结束
         */
        $this -> is_end = true;
        /**
         * 发送状态码
         */
        $this -> getRawResponse() -> status($this -> http_code);
        /**
         * 结束请求
         */
        return $this -> getRawResponse() -> end();
    }

    /**
     * 获取原生的响应句柄
     * @return mixed|null|\swoole_http_response
     */
    public function getRawResponse()
    {
        return $this -> response;
    }

    /**
     * 请求是否已经结束
     * @return bool|mixed
     */
    public function ResponseIsEnd()
    {
        return $this -> is_end;
    }
}