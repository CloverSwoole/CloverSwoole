<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * 响应
 * Class Response
 * @package Itxiao6\Framework\Facade\Http
 */
abstract class Response
{
    /**
     * 向客户端写入内容
     * @param $content
     * @return mixed
     */
    abstract public function writeContent($content);

    /**
     * 发送协议头
     * @param $headers
     * @return mixed
     */
    abstract public function withHeader(Headers $headers);

    /**
     * 响应状态码
     * @param $code
     * @return mixed
     */
    abstract public function withStatus($code);

    /**
     * 发送cookie
     * @param $headers
     * @return mixed
     */
    abstract public function withCookie(Cookies $cookie);

    /**
     * 结束请求
     * @return mixed
     */
    abstract public function endResponse();

    /**
     * 请求是否已经结束
     * @return mixed
     */
    abstract public function ResponseIsEnd();

    /**
     * 获取原生的响应句柄
     * @return mixed
     */
    abstract public function getRawResponse();
}