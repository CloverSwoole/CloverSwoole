<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * Class Cookies
 * @package Itxiao6\Framework\Facade\Http
 */
class Cookies
{
    protected $cookies = [];
    /**
     * 构造器
     * Cookies constructor.
     */
    public function __construct()
    {

    }
    public function boot($cookies = [])
    {
        $this -> cookies = $cookies;
    }
    public function getCookies()
    {
        return $this -> cookies;
    }
    public function getCookie($name)
    {
        return $name != null?(isset($this -> cookies[$name])?$this -> cookies[$name]:null):null;
    }
}