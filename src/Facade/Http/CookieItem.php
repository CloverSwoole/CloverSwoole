<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;

/**
 * Class CookieItem
 * @package CloverSwoole\CloverSwoole\Facade\Http
 */
class CookieItem
{
    protected $name = '';
    protected $value = '';
    protected $expire = '';
    protected $path = '';
    protected $domain = '';
    protected $secure = '';
    protected $httponly = '';

    public function __construct($name,$value,$expire = 0,$path = '/',$domain='',$secure=false,$httponly=false)
    {
        $this -> name = $name;
        $this -> value = $value;
        $this -> expire = $expire;
        $this -> path = $path;
        $this -> domain = $domain;
        $this -> secure = $secure;
        $this -> httponly = $httponly;
    }
    public function getExpire()
    {
        return $this -> expire;
    }
    public function getPath()
    {
        return $this -> path;
    }
    public function getDemain()
    {
        return $this -> domain;
    }
    public function getSecure()
    {
        return $this -> secure;
    }
    public function getHttpoly()
    {
        return $this -> httponly;
    }
    public function getName()
    {
        return $this -> name;
    }
    public function getValue()
    {
        return $this -> value;
    }
}