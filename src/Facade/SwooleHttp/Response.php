<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use EasySwoole\Http\Message\Stream;
use Itxiao6\Framework\Facade\Http\CookieItem;
use Itxiao6\Framework\Facade\Http\Cookies;
use Itxiao6\Framework\Facade\Http\HeaderItem;
use Itxiao6\Framework\Facade\Http\Headers;
use Itxiao6\Framework\Facade\Http\Status;

/**
 * Response 响应类
 * Class Response
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class Response extends \Itxiao6\Framework\Facade\Http\Response
{
    /**
     * 构造方法
     * Response constructor.
     * @param array|null $headers
     * @param Stream|null $body
     * @param string $protocolVersion
     */
    public function __construct()
    {
        $this -> withHeader(new HeaderItem('Server','Minkernel'));
    }
    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return mixed|void
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this -> getRawResponse() -> status($code);
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
     * 发送响应头部信息
     */
    protected function sendHeaders()
    {
        if(!(is_array($this -> response_headers) && count($this -> response_headers) > 0 )){
            return ;
        }
        foreach ($this -> response_headers as $header_item){
            $this -> getRawResponse() -> header($header_item -> getName(),$header_item -> getValue());
        }
    }
    /**
     * 设置请求cookie
     */
    protected function sendCookie()
    {
        if(!(is_array($this -> response_cookies) && count($this -> response_cookies) > 0 )){
            return ;
        }
        foreach ($this -> response_cookies as $cookie_item){
            $this -> getRawResponse() -> cookie($cookie_item -> getName(),$cookie_item -> getValue(),$cookie_item -> getExpire(),$cookie_item -> getPath(),$cookie_item -> getDemain(),$cookie_item -> getSecure(),$cookie_item -> getHttpoly());
        }
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
         * 发送协议头
         */
        $this -> sendHeaders();
        /**
         * 发送Cookie
         */
        $this -> sendCookie();
        /**
         * 标识响应已经结束
         */
        $this -> is_end = true;
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
}