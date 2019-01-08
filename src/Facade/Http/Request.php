<?php
namespace Itxiao6\Framework\Facade\Http;

/**
 * 请求模型
 * Class Request
 * @package Itxiao6\Framework\Facade\Http
 */
abstract class Request
{
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
     * 获取请求时间
     * @return mixed
     */
    abstract public function getRawRequest();

    /**
     * 获取GET 参数
     * @return mixed
     */
    abstract public function getGETParam();

    /**
     * 获取POST 参数
     * @return mixed
     */
    abstract public function getPOSTParam();

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