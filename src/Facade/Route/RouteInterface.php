<?php
namespace CloverSwoole\CloverSwoole\Facade\Route;

use Illuminate\Container\Container;
use CloverSwoole\CloverSwoole\Facade\Http\Request;
use CloverSwoole\CloverSwoole\Facade\Http\Response;

/**
 * 路由门面接口
 * Interface RouteInterface
 * @package CloverSwoole\CloverSwoole\Facade\Route
 */
interface RouteInterface
{
    /**
     * 获取响应
     * @return Response|null
     */
    public function getResponse();
    /**
     * 获取请求
     * @return Request|null
     */
    public function getRequest();
    /**
     * 结束路由处理
     */
    public function endRoute();
    /**
     * 路由处理是否已经处理完毕
     * @return bool
     */
    public function routeIsEnd();
    /**
     * 启动组件
     * @param Request $request
     * @param Response $response
     */
    public function boot(Request $request,Response $response);
}