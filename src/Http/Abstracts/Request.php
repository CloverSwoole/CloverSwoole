<?php
namespace CloverSwoole\Http\Abstracts;
/**
 * Interface Request
 * @package CloverSwoole\Http\Abstracts
 */
interface Request
{
    /**
     * 获取所有的查询参数
     * @return array|mixed
     */
    public function getQuerys();
    /**
     * 获取指定的查询参数
     * @param null|string $name
     * @return mixed|string
     */
    public function getQuery($name = null);
    /**
     * 获取所有请求头部信息
     * @return mixed|array
     */
    public function getHeaders();
    /**
     * 获取指定请求头部信息
     * @param null|string $name
     * @return mixed
     */
    public function getHeader($name = null);
    /**
     * 获取请求的URI
     * @return mixed
     */
    public function getUri();
    /**
     * 获取所有的cookie
     * @return mixed
     */
    public function getCookies();
    /**
     * 获取指定的cookie
     * @param null|string $name
     * @return mixed
     */
    public function getCookie($name = null);
    /**
     * 获取请求方法
     * @return mixed
     */
    public function getRequestMethod();
    /**
     * 获取所有server 信息
     * @return mixed|array
     */
    public function getServers();
    /**
     * 获取指定server 信息
     * @param null|string $name
     * @return mixed|string
     */
    public function getServer($name = null);
    /**
     * 获取请求内容类型
     * @return mixed
     */
    public function getContentType();
    /**
     * 获取来源地址
     * @return mixed
     */
    public function getOriginLocation();
    /**
     * 获取用户代理信息
     * @return mixed
     */
    public function getUserAgent();
    /**
     * 获取客户端接受的语言
     * @return mixed
     */
    public function getAcceptLanguage();
    /**
     * 获取指定的GET参数
     * @param null|string  $name
     * @return mixed
     */
    public function getGetParam($name = null);
    /**
     * 获取所有的GET参数
     * @return mixed
     */
    public function getGetParams();

    /**
     * 获取所有的post 数据
     * @return mixed
     */
    public function getPostParams();

    /**
     * 获取指定的post 数据
     * @param null|string  $name
     * @return mixed
     */
    public function getPostParam($name = null);
}