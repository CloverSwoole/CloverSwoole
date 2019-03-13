<?php
namespace CloverSwoole\CloverSwoole\Facade\FastCgiServer;

/**
 * FastCgiServer 响应实例类
 * Class Response
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class Response extends \CloverSwoole\CloverSwoole\Facade\Http\Response
{
    /**
     * @var null|mixed
     */
    protected $response = null;
    /**
     * 启动组件
     * @param $response
     * @return $this
     */
    public function boot($response)
    {
        $this -> response = $response;
        /**
         * 默认Server 名称
         */
        $this -> withHeader('Server','CloverSwoole');
        /**
         * 默认页面内容类型及编码
         */
        $this -> withHeader('Content-Type','text/html;charset=utf-8');
        return $this;
    }

    /**
     * 结束请求
     * @return mixed|void
     */
    public function sendEndResponse()
    {
        $this -> response -> end();
    }
    /**
     * 发送状态码
     * @param $code
     * @return mixed|void
     */
    public function sendStatusCode($code)
    {
        return $this -> response -> status($code);
    }
    /**
     * 发送内容到客户端
     * @param $content
     * @return mixed|void
     */
    public function sendContent($content)
    {
        return $this -> response -> write($content);
    }
    /**
     * 设置Cookie
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return mixed|void
     */
    public function sendCookie(string $key, string $value = '', int $expire = 0 , string $path = '/', string $domain  = '', bool $secure = false , bool $httponly = false)
    {
        return $this -> response -> cookie(...func_get_args());
    }

    /**
     * 发送响应头部信息
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function sendHeader(string $key,$value)
    {
        return $this -> response -> header(...func_get_args());
    }

    /**
     * 响应一个重定向
     * @param string $url
     * @param int $http_code
     * @return mixed|void
     */
    public function sendRedirect(string $url,int $http_code = 302)
    {
        /**
         * Swoole 重定向
         */
        return $this -> getRawResponse() -> redirect($url,$http_code);
    }
    /**
     * 发送文件
     * @param $filename
     * @param int $offset
     * @param int $length
     * @return mixed|void
     */
    public function sendFile($filename,$offset = 0,$length = 0)
    {
        /**
         * 响应句柄内部处理
         */
        if($offset == 0 && $length == 0){
            parent::endResponse();
        }
        /**
         * 发送文件到客户端
         */
        $this -> getRawResponse() -> sendfile($filename,$offset,$length);
    }

    /**
     * 获取原生的响应句柄
     * @return mixed|null|\swoole_http_response
     */
    public function getRawResponse()
    {
        return $this -> response;
    }
}