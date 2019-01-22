<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;
/**
 * 请求 Headers
 * Class RequestHeaders
 * @package CloverSwoole\CloverSwoole\Facade\Http
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