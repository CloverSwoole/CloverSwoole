<?php
namespace Itxiao6\Framework\Facade\SwooleHttp\EasySwoole\Http;

use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Illuminate\Container\Container;

class Controller extends \EasySwoole\Http\AbstractInterface\Controller
{
    /**
     * @var null|Container
     */
    protected $app = null;
    public function __hook(?string $actionName, Request $request, Response $response,?Container $container = null): void
    {
        $this -> app = $container;
        parent::__hook($actionName, $request, $response); // TODO: Change the autogenerated stub
    }

    public function index(){}
    /**
     * 返回JSON数据
     * @param $data
     * @param bool $is_end
     */
    protected function ReturnJosn($data,$is_end = false)
    {
        /**
         * 判断请求是否已经结束
         */
        if($this -> response() -> isEndResponse()){return ;}
        /**
         * 响应数据
         */
        $this->response()->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        /**
         * 更改协议头
         */
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
        /**
         * 允许所有跨域请求
         */
        $this->response()->withHeader('Access-Control-Allow-Origin','*');
        /**
         * 允许跨域的方法
         */
        $this->response()->withHeader('Access-Control-Allow-Method','POST');
        /**
         * 允许修改的协议头
         */
        $this->response()->withHeader('Access-Control-Allow-Headers','accept, content-type');
        /**
         * 响应码
         */
        $this->response()->withStatus(Status::CODE_OK);
        /**
         * 判断是否要结束请求
         */
        if($is_end){$this -> EndResponse();}
    }
    protected function gc()
    {
        parent::gc();
//        var_dump('class :'.static::class.' is recycle to pool');
    }
}