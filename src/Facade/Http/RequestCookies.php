<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * 请求Cookies
 * Class RequestCookies
 * @package Itxiao6\Framework\Facade\Http
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