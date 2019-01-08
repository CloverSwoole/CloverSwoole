<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * 头部信息
 * Class Headers
 * @package Itxiao6\Framework\Facade\Http
 */
class Headers
{
    protected $headers = [];

    /**
     * 构造器
     * Headers constructor.
     */
    public function __construct()
    {

    }
    public function boot($headers = [])
    {
        $this -> headers = [];
        return $this;
    }

    /**
     * 新增头部信息
     * @param $name
     * @param $value
     * @return $this
     */
    public function addHeader($name,$value)
    {
        $this -> headers[$name] = $value;
        return $this;
    }

    /**
     * 获取所有Header
     */
    public function getHeaders()
    {
        return $this -> headers;
    }

    /**
     * 获取指定的头部信息
     */
    public function getHeader($name = null)
    {
        return $name != null?(isset($this -> headers[$name])?$this -> headers[$name]:null):null;
    }
}