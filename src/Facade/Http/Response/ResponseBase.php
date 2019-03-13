<?php
namespace CloverSwoole\CloverSwoole\Facade\Http\Exception;


/**
 * 响应异常捕获基础类
 * Class ResponseBase
 * @package CloverSwoole\CloverSwoole\Facade\Http\Exception
 */
class ResponseBase extends HttpBaseException
{
    /**
     * 响应数据
     * @var array|mixed|null|string
     */
    protected $data = null;
    /**
     * 响应头部信息
     * @var array
     */
    protected $headers = [];
    /**
     * 响应时的cookie
     * @var array
     */
    protected $cookies = [];

    /**
     * 创建响应
     * @param string|mixed|array $data
     * @param array $headers
     * @param array $cookies
     * @return ResponseBase
     */
    public static function create($data = '',$headers = [],$cookies = [])
    {
        return (new static(...func_get_args()));
    }

    /**
     * 基础构造器
     * ResponseBase constructor.
     * @param string|mixed|array $data
     * @param array $headers
     * @param array $cookies
     */
    public function __construct($data = '',$headers = [],$cookies = [])
    {
        $this -> data = $data;
        $this -> headers = $headers;
        $this -> cookies = $cookies;
    }

    /**
     * 发送响应
     * @throws ResponseBase
     */
    public function send()
    {
        throw $this;
    }

    /**
     * 获取数据
     * @return null|string
     */
    public function getData()
    {
        return $this -> data;
    }

    /**
     * 获取headers
     * @return array
     */
    public function getHeaders()
    {
        return $this -> headers;
    }

    /**
     * 获取cookies
     * @return array
     */
    public function getCookies()
    {
        return $this -> cookies;
    }
}