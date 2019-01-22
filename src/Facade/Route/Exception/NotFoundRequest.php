<?php
namespace CloverSwoole\CloverSwoole\Facade\Route\Exception;
use CloverSwoole\CloverSwoole\Facade\Http\Request;
use CloverSwoole\CloverSwoole\Facade\Http\Response;
use Throwable;

/**
 * 找不到指定的请求
 * Class NotFoundRequest
 * @package CloverSwoole\CloverSwoole\Facade\Route\Exception
 */
class NotFoundRequest extends RouteBaseException
{
    /**
     * 实例化
     * NotFoundRequest constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param Request|null $request
     * @param Response|null $response
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null,Request $request = null,Response $response = null)
    {
        parent::__construct($message, $code, $previous);
    }
}