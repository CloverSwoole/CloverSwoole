<?php
namespace CloverSwoole\CloverSwoole\Facade\Http\Abstracts;
/**
 * Class Response
 * @package CloverSwoole\CloverSwoole\Facade\Http\Abstracts
 */
interface Response
{
    /**
     * 发送内容
     * @param $content
     * @return mixed
     */
    public function sendContent($content);

    /**
     * 发送结束请求
     * @return mixed
     */
    function sendEndResponse();

    /**
     * 发送文件
     * @param $filename
     * @param int $offset
     * @param int $length
     * @return mixed
     */
    public function sendFile($filename,$offset = 0,$length = 0);

    /**
     * 发送响应头headers
     * @param $name
     * @param $values
     * @return mixed
     */
    public function sendHeader(string $name,$values);

    /**
     * 发送状态码
     * @param $code
     * @return mixed
     */
    public function sendStatusCode($code);

    /**
     * 重定向
     * @param string $url
     * @param int $http_code
     * @return mixed
     */
    public function sendRedirect(string $url,int $http_code = 302);

    /**
     * 发送Cookies
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return mixed
     */
    public function sendCookie(string $key, string $value = '', int $expire = 0 , string $path = '/', string $domain  = '', bool $secure = false , bool $httponly = false);
}