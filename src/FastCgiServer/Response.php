<?php

namespace CloverSwoole\FastCgiServer;

/**
 * FastCgiServer 响应实例类
 * Class Response
 * @package CloverSwoole\CloverSwoole\Facade\SwooleHttp
 */
class Response extends \CloverSwoole\Http\Response
{
    /**
     * @var null|mixed
     */
    protected $response = null;

    /**
     * 启动组件
     * Response constructor.
     */
    public function __construct()
    {
        /**
         * 默认Server 名称
         */
        $this->withHeader('Server', 'CloverSwoole');
        /**
         * 默认页面内容类型及编码
         */
        $this->withHeader('Content-Type', 'text/html;charset=utf-8');
        return $this;
    }

    /**
     * 结束请求
     * @return mixed|void
     */
    public function sendEndResponse()
    {
        exit();
    }

    /**
     * 发送状态码
     * @param $code
     * @return mixed|void
     */
    public function sendStatusCode($code)
    {
        return $this->send_http_status($code);
    }

    /**
     * 发送内容到客户端
     * @param $content
     * @return mixed|void
     */
    public function sendContent($content)
    {
        echo $content;
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
    public function sendCookie(string $key, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false)
    {
        return setcookie(...func_get_args());
    }

    /**
     * 发送响应头部信息
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function sendHeader(string $key, $value)
    {
        return header(...func_get_args());
    }

    /**
     * 响应一个重定向
     * @param string $url
     * @param int $http_code
     * @return mixed|void
     */
    public function sendRedirect(string $url, int $http_code = 302)
    {
        /**
         * Swoole 重定向
         */
        header('Location: ' . $url);
    }

    /**
     * 发送文件
     * @param $filename
     * @param int $offset
     * @param int $length
     * @return mixed|void
     */
    public function sendFile($filename, $offset = 0, $length = 0)
    {
        /**
         * 响应句柄内部处理
         */
        if ($offset == 0 && $length == 0) {
            parent::endResponse();
        }
        /**
         * 发送文件到客户端
         */
        $this->withContent(file_get_contents($filename));
    }

    /**
     * 对http协议的状态设定，跳转页面中需要经常使用的函数
     * @param $code
     */
    protected function send_http_status($code)
    {

        static $_status = array(

// Informational 1xx

            100 => 'Continue',

            101 => 'Switching Protocols',

// Success 2xx

            200 => 'OK',

            201 => 'Created',

            202 => 'Accepted',

            203 => 'Non-Authoritative Information',

            204 => 'No Content',

            205 => 'Reset Content',

            206 => 'Partial Content',

// Redirection 3xx

            300 => 'Multiple Choices',

            301 => 'Moved Permanently',

            302 => 'Moved Temporarily ',  // 1.1

            303 => 'See Other',

            304 => 'Not Modified',

            305 => 'Use Proxy',

// 306 is deprecated but reserved

            307 => 'Temporary Redirect',

// Client Error 4xx

            400 => 'Bad Request',

            401 => 'Unauthorized',

            402 => 'Payment Required',

            403 => 'Forbidden',

            404 => 'Not Found',

            405 => 'Method Not Allowed',

            406 => 'Not Acceptable',

            407 => 'Proxy Authentication Required',

            408 => 'Request Timeout',

            409 => 'Conflict',

            410 => 'Gone',

            411 => 'Length Required',

            412 => 'Precondition Failed',

            413 => 'Request Entity Too Large',

            414 => 'Request-URI Too Long',

            415 => 'Unsupported Media Type',

            416 => 'Requested Range Not Satisfiable',

            417 => 'Expectation Failed',

// Server Error 5xx

            500 => 'Internal Server Error',

            501 => 'Not Implemented',

            502 => 'Bad Gateway',

            503 => 'Service Unavailable',

            504 => 'Gateway Timeout',

            505 => 'HTTP Version Not Supported',

            509 => 'Bandwidth Limit Exceeded'

        );
        /**
         * 判断要发送的状态 是否合法
         */
        if (array_key_exists($code, $_status)) {
            header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        }
    }
}