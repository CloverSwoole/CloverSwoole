<?php
namespace Itxiao6\Framework\Facade\Route\Exception;
use Itxiao6\Framework\Facade\Http\Request;
use Itxiao6\Framework\Facade\Http\Response;
use Throwable;

/**
 * 找不到指定的请求
 * Class NotFoundRequest
 * @package Itxiao6\Framework\Facade\Route\Exception
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