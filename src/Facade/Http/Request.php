<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;

/**
 * Http 请求基类
 * Class Request
 * @package CloverSwoole\CloverSwoole\Facade\Http
 */
abstract class Request implements \CloverSwoole\CloverSwoole\Facade\Http\Abstracts\Request
{
    /**
     * 全局访问的实例
     * @var null | Request
     */
    protected static $interface = null;
    /**
     * 获取响应句柄
     * @return Request|null
     */
    public static function getInterface()
    {
        return static::$interface;
    }
    /**
     * 设置全局访问
     * @param $bool
     */
    public function setAsGlobal($bool = true)
    {
        if($bool){
            static::$interface = $this;
        }else{
            static::$interface = null;
        }
    }
    /**
     * 获取请求内容的类型
     * @return mixed|void|string
     */
    public function getContentType()
    {
        return $this -> getHeader('content-type');
    }
    /**
     * 获取来源地址
     * @return mixed|string
     */
    public function getOriginLocation()
    {
        return $this -> getHeader('origin');
    }
    /**
     * 获取客户端接受的语言
     * @return mixed|string
     */
    public function getAcceptLanguage()
    {
        return $this -> getHeader('accept-language');
    }

    /**
     * 获取用户代理信息
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this -> getHeader('user-agent');
    }
}