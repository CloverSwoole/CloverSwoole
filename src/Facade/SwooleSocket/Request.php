<?php
namespace CloverSwoole\CloverSwoole\Facade\SwooleSocket;
use CloverSwoole\CloverSwoole\Facade\Utility\FindVar;
use CloverSwoole\CloverSwoole\Facade\Utility\Xml;

/**
 * SokcetSwoole 请求实例类
 * Class Request
 * @package CloverSwoole\CloverSwoole\Facade\SwooleSocket
 */
class Request extends \CloverSwoole\CloverSwoole\Facade\Http\Request
{
    /**
     * 查询参数
     * @var array|null
     */
    protected $query = null;
    /**
     * POST 数据
     * @var array|null
     */
    protected $post = null;
    /**
     * @var null |\Swoole\Http\Request
     */
    protected $request = null;
    /**
     * 创建一个请求实例
     * @param $request
     * @return $this
     */
    public function boot($request)
    {
        $this -> request = $request;
        return $this;
    }
    /**
     * 获取请求所有的头部信息
     * @return array|mixed
     */
    public function getHeaders()
    {
        return $this -> request -> header;
    }
    /**
     * 获取指定的请求头部信息
     * @param $name
     * @return mixed|null
     */
    public function getHeader($name = null)
    {
        return FindVar::findVarByExpression($name,$this -> request -> header);
    }
    /**
     * 获取请求所有的Cookie
     * @return array|mixed|null
     */
    public function getCookies()
    {
        return $this -> request -> cookie;
    }
    /**
     * 获取指定的cookie
     * @param $name
     * @return array|mixed
     */
    public function getCookie($name = null)
    {
        return FindVar::findVarByExpression($name,$this -> request -> cookie);
    }
    /**
     * 获取请求方法
     * @return mixed|string
     */
    public function getRequestMethod()
    {
        return isset($this -> request -> server['request_method'])?$this -> request -> server['request_method']:'GET';
    }
    /**
     * 获取原生请求
     * @return mixed|void|\swoole_http_request
     */
    public function getRawRequest()
    {
        return $this -> request;
    }
    /**
     * 获取请求的路径
     * @return mixed
     */
    public function getUri()
    {
        return isset($this -> request -> server['request_uri'])?$this -> request -> server['request_uri']:null;
    }
    /**
     * 获取查询参数
     * @return array|mixed|null
     */
    public function getQuerys()
    {
        if($this -> query === null || (!is_array($this -> query))){
            parse_str(isset($this -> request -> server['query_string'])?$this -> request -> server['query_string']:'',$this -> query);
        }
        return $this -> query;
    }
    /**
     * 获取指定的查询参数
     * @param string $name
     * @return mixed|null|string
     */
    public function getQuery($name = null)
    {
        if($this -> query === null || (!is_array($this -> query))){
            parse_str(isset($this -> request -> server['query_string'])?$this -> request -> server['query_string']:'',$this -> query);
        }
        return FindVar::findVarByExpression($name,$this -> getQuerys());
    }
    /**
     * 获取服务信息
     * @return array|mixed|void
     */
    public function getServers()
    {
        return $this -> request -> server;
    }
    /**
     * 获取指定服务信息
     * @param null $name
     * @return array|mixed|string
     */
    public function getServer($name = null)
    {
        return FindVar::findVarByExpression($name,$this -> getServers());
    }
    /**
     * 获取所有的 GET 参数
     * @return mixed
     */
    public function getGetParams()
    {
        return $this -> request -> get;
    }
    /**
     * 获取指定的 GET 参数
     * @param null $name
     * @return array|mixed
     */
    public function getGetParam($name = null)
    {
        return FindVar::findVarByExpression($name,$this -> getGetParams());
    }

    /**
     * 获取指定的post 数据
     * @param null $name
     * @return array|mixed
     */
    public function getPostParam($name = null)
    {
        return FindVar::findVarByExpression($name,$this -> getPostParams());
    }

    /**
     * 获取所有的post 数据
     * @return array|mixed
     */
    public function getPostParams()
    {
        if($this -> post === null || (!is_array($this -> post))){
            $this -> post = array_merge(is_array($this -> request -> post)?$this -> request -> post:[],$this -> getRawPostData());
        }
        return $this -> post;
    }

    /**
     * 处理原生POST 数据
     * @return array|mixed
     */
    protected function getRawPostData()
    {
        switch($this -> getContentType()){
            case 'application/json':
                $returnData = json_decode($this -> request -> getData(),1);
                break;
            case 'application/xml':
                $returnData = Xml::arrayToXml($this -> request -> getData());
                break;
            // TODO 更多格式的兼容及拓展
            default:
                $returnData = [];
        }
        return is_array($returnData)?$returnData:[];
    }
}