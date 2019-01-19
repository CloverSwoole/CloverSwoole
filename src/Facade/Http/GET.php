<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * Request GET
 * Class GET
 * @package Itxiao6\Framework\Facade\Http
 */
class GET
{
    protected $get = [];
    /**
     * 构造器
     * Cookies constructor.
     */
    public function __construct()
    {

    }
    public function boot($get = [])
    {
        $this -> get = $get;
        return $this;
    }
    public function getGets()
    {
        return $this -> get;
    }
    public function getGet($name)
    {
        return $name != null?(isset($this -> get[$name])?$this -> get[$name]:null):null;
    }
}