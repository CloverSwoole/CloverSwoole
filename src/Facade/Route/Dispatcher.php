<?php
namespace Itxiao6\Framework\Facade\Route;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\Dispatcher\GroupCountBased;
use Illuminate\Container\Container;

/**
 * 调度器
 * Class Dispatcher
 * @package Itxiao6\Framework\Facade\Route
 */
class Dispatcher
{
    /**
     * @var int|string
     */
    protected $controllerNameSpacePrefix = 5;
    /**
     * 默认每个进程15个控制器，若每个控制器一个持久连接，那么8 worker  就是120连接了
     * Dispatcher constructor.
     * @param string $controllerNameSpace
     * @param int $maxDepth
     * @param int $maxPoolNum
     * @throws \Exception
     */
    function __construct(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        /**
         * 获取配置
         */
        $container -> make(Config::class);
        /**
         * 获取动态路由
         */
        try{
            $path = UrlParser::pathInfo($request->getUri()->getPath());
            dump($path);
            return ;
        }catch (\Throwable $throwable){
            throw new \Exception($throwable->getMessage());
        }
    }
}