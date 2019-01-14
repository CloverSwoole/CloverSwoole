<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * 请求 Headers
 * Class RequestHeaders
 * @package Itxiao6\Framework\Facade\Http
 */
class RequestHeaders
{
    /**
     * 请求头
     * @var array
     */
    protected $request_headers = [];

    /**
     * @param $headers
     */
    public function boot($headers)
    {
        $this -> request_headers = $headers;
    }
}