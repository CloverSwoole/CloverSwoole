<?php

namespace CloverSwoole\FastCgiServer;

use CloverSwoole\Utility\FindVar;
use CloverSwoole\Utility\Xml;

/**
 * FastCgiServer 请求实例类
 * Class Request
 * @package CloverSwoole\FastCgiServer
 */
class Request extends \CloverSwoole\Http\Request
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

    public function __construct()
    {

    }

    /**
     * 获取请求所有的头部信息
     * @return array|mixed
     */
    public function getHeaders()
    {
        return $_SERVER;
    }

    /**
     * 获取指定的请求头部信息
     * @param $name
     * @return mixed|null
     */
    public function getHeader($name = null)
    {
        return FindVar::findVarByExpression($name, $_SERVER);
    }

    /**
     * 获取请求所有的Cookie
     * @return array|mixed|null
     */
    public function getCookies()
    {
        return $_COOKIE;
    }

    /**
     * 获取指定的cookie
     * @param $name
     * @return array|mixed
     */
    public function getCookie($name = null)
    {
        return FindVar::findVarByExpression($name, $_COOKIE);
    }

    /**
     * 获取请求方法
     * @return mixed|string
     */
    public function getRequestMethod()
    {
        return $this->getServer('REQUEST_METHOD');
    }

    /**
     * 获取请求的路径
     * @return mixed
     */
    public function getUri()
    {
        return $this->getServer('REQUEST_URI');
    }

    /**
     * 获取查询参数
     * @return array|mixed|null
     */
    public function getQuerys()
    {
        if ($this->query === null || (!is_array($this->query))) {
            parse_str($this->getServer('QUERY_STRING'), $this->query);
        }
        return $this->query;
    }

    /**
     * 获取指定的查询参数
     * @param string $name
     * @return mixed|null|string
     */
    public function getQuery($name = null)
    {
        if ($this->query === null || (!is_array($this->query))) {
            parse_str($this->getServer('QUERY_STRING'), $this->query);
        }
        return FindVar::findVarByExpression($name, $this->getQuerys());
    }

    /**
     * 获取服务信息
     * @return array|mixed|void
     */
    public function getServers()
    {
        return $_SERVER;
    }

    /**
     * 获取指定服务信息
     * @param null $name
     * @return array|mixed|string
     */
    public function getServer($name = null)
    {
        return FindVar::findVarByExpression($name, $this->getServers());
    }

    /**
     * 获取所有的 GET 参数
     * @return mixed
     */
    public function getGetParams()
    {
        return $_GET;
    }

    /**
     * 获取指定的 GET 参数
     * @param null $name
     * @return array|mixed
     */
    public function getGetParam($name = null)
    {
        return FindVar::findVarByExpression($name, $this->getGetParams());
    }

    /**
     * 获取指定的post 数据
     * @param null $name
     * @return array|mixed
     */
    public function getPostParam($name = null)
    {
        return FindVar::findVarByExpression($name, $this->getPostParams());
    }

    /**
     * 获取所有的post 数据
     * @return array|mixed
     */
    public function getPostParams()
    {
        if ($this->post === null || (!is_array($this->post))) {
            $this->post = array_merge(is_array($_POST) ? $_POST : [], $this->getRawPostData());
        }
        return $this->post;
    }

    /**
     * 处理原生POST 数据
     * @return array|mixed
     */
    protected function getRawPostData()
    {
        switch ($this->getContentType()) {
            case 'application/json':
                $returnData = json_decode(file_get_contents("php://input"), 1);
                break;
            case 'application/xml':
                $returnData = Xml::arrayToXml(file_get_contents("php://input"));
                break;
            // TODO 更多格式的兼容及拓展
            default:
                $returnData = [];
        }
        return is_array($returnData) ? $returnData : [];
    }
}