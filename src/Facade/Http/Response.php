<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * Http 响应基类
 * Class Response
 * @package Itxiao6\Framework\Facade\Http
 */
abstract class Response
{
    /**
     * @var null|mixed
     */
    protected $response = null;
    /**
     * 响应头
     * @var null|array
     */
    protected $response_headers = null;
    /**
     * 响应cookie
     * @var null | array
     */
    protected $response_cookies = null;
    /**
     * 响应内容
     * @var string
     */
    protected $response_contents = '';
    /**
     * 默认为成功
     * @var int
     */
    protected $response_http_code = Status::CODE_OK;
    /**
     * @var bool
     */
    protected $is_end = false;
    /**
     * 注入 Cookie
     * @param $cookie
     * @return mixed|void
     */
    public function withCookie(CookieItem $cookies)
    {
        $this -> response_cookies = $cookies;
    }
    /**
     * 获取原生的响应句柄
     * @return null|\swoole_http_response|mixed
     */
    public function getRawResponse(){
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
    /**
     * 响应头
     * @param HeaderItem $headers
     * @return mixed|void
     */
    public function withHeader(HeaderItem $headers)
    {
        $this -> response_headers[] = $headers;
    }
    /**
     * 响应重定向 302
     * @param $url
     * @return mixed
     */
    public function redirect($url)
    {
        $this -> is_end = true;
    }
    /**
     * 向客户端写入内容
     * @param $content
     * @return mixed
     */
    abstract public function writeContent($content);
    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return mixed|void
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this -> response_http_code = $code;
    }
    /**
     * 结束请求
     * @return mixed
     */
    abstract public function endResponse();
    /**
     * 发送文件
     * @return mixed
     */
    public function sendFile($filename,$offset = 0,$length = 0)
    {
        /**
         * 标识响应已经结束
         */
        $this -> is_end = true;
        /**
         * 发送协议头
         */
        $this -> sendHeaders();
        /**
         * 发送Cookie
         */
        $this -> sendCookie();
        /**
         * 响应状态码
         */
        $this -> getRawResponse() -> status($this -> response_http_code);
    }
}