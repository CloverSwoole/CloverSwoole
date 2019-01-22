<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;
/**
 * 请求Cookies
 * Class RequestCookies
 * @package CloverSwoole\CloverSwoole\Facade\Http
 */
class RequestCookies
{
    /**
     * @var array
     */
    protected $request_cookies = [];
    /**
     * @param $cookies
     */
    public function boot($cookies)
    {
        $this -> request_cookies = $cookies;
    }
}