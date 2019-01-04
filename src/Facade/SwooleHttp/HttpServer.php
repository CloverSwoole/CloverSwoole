<?php
namespace Itxiao6\Framework\Facade\SwooleHttp;
use Illuminate\Container\Container;
use Itxiao6\Framework\Facade\Route\Route;
use Itxiao6\Framework\Facade\Route\RouteInterface;

/**
 * Http Swoole
 * Class HttpServer
 * @package Itxiao6\Framework\Facade\SwooleHttp
 */
class HttpServer
{
    /**
     * 容器
     * @var null | Container
     */
    protected $container = null;

    /**
     * 构造方法
     * HttpServer constructor.
     * @param Container|null $container
     */
    public function __construct(?Container $container = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }

    }

    /**
     * 请求到达时
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     */
    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        /**
         * 解析路由
         */
//        $this -> container -> make(RouteInterface::class) -> dispatcher();

        dump($request);
        return ;

        $path = UrlParser::pathInfo($request->getUri()->getPath());
        if($this->router instanceof GroupCountBased){
            $handler = null;
            $routeInfo = $this->router->dispatch($request->getMethod(),$path);
            if($routeInfo !== false){
                switch ($routeInfo[0]) {
                    case \FastRoute\Dispatcher::NOT_FOUND:{
                        $handler = $this->routerRegister->getRouterNotFoundCallBack();
                        break;
                    }
                    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:{
                        $handler = $this->routerRegister->getMethodNotAllowCallBack();
                        break;
                    }
                    case \FastRoute\Dispatcher::FOUND:{
                        $func = $routeInfo[1];
                        $vars = $routeInfo[2];
                        if(is_callable($func)){
                            try{
                                return ;
                                call_user_func_array($func,array_merge([$request,$response],array_values($vars)));
                                if ($response->isEndResponse()) {
                                    return;
                                }
                            }catch (\Throwable $throwable){
                                $this->hookThrowable($throwable,$request,$response);
                                //出现异常的时候，不在往下dispatch
                                return;
                            }
                        }else if(is_string($func)){
                            $path = $func;
                            $data = $request->getQueryParams();
                            $request->withQueryParams($vars+$data);
                            $pathInfo = UrlParser::pathInfo($func);
                            $request->getUri()->withPath($pathInfo);
                        }
                        //命中路由的时候，直接跳转到分发逻辑
//                        goto dispatch;
                        break;
                    }
                    default:{
                        $handler = $this->routerRegister->getRouterNotFoundCallBack();
                        break;
                    }
                }
            }
            //如果handler不为null，那么说明，非为 \FastRoute\Dispatcher::FOUND ，因此执行
            if(is_callable($handler)){
                try{
                    call_user_func($handler,$request,$response);
                }catch (\Throwable $throwable){
                    $this->hookThrowable($throwable,$request,$response);
                    //出现异常的时候，不在往下dispatch
                    return;
                }
            }
            /*
                * 全局模式的时候，都拦截。非全局模式，否则继续往下
            */
            if($this->routerRegister->isGlobalMode()){
                return;
            }
        }

        //如果路由中结束了响应，则不再往下
        if($response->isEndResponse()){
            return;
        }
    }
}