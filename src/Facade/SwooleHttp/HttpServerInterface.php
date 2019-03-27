<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleHttp;
use Swoole\Http\Response;
use Swoole\Http\Request;
/**
 * Interface HttpServerInterface
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
interface HttpServerInterface
{
    /**
     * @param Request $request_raw
     * @param Response $response_raw
     * @return mixed
     */
    public function onRequest(Request $request_raw, Response $response_raw);
}