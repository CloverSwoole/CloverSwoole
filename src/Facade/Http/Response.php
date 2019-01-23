<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;
use CloverSwoole\CloverSwoole\Facade\VarDumper\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Http 响应基类
 * Class Response
 * @package CloverSwoole\CloverSwoole\Facade\Http
 */
abstract class Response
{
    /**
     * @var null|mixed
     */
    protected $response = null;
    /**
     * 响应头
     * @var null|array
     */
    protected $response_headers = null;
    /**
     * 响应cookie
     * @var null | array
     */
    protected $response_cookies = null;
    /**
     * 响应内容
     * @var string
     */
    protected $response_contents = '';
    /**
     * 默认为成功
     * @var int
     */
    protected $response_http_code = Status::CODE_OK;
    /**
     * @var bool
     */
    protected $is_end = false;
    /**
     * @var null | Response
     */
    protected static $global_response = null;
    /**
     * 设置全局访问
     * @param $bool
     */
    public function setAsGlobal($bool = true)
    {
        if($bool){
            static::$global_response = $this;
        }else{
            static::$global_response = null;
        }
    }
    /**
     * 获取响应句柄
     * @return Response|null
     */
    public static function getInterface()
    {
        return static::$global_response;
    }
    /**
     * 注入 Cookie
     * @param $cookie
     * @return mixed|void
     */
    public function withCookie(CookieItem $cookies)
    {
        $this -> response_cookies = $cookies;
    }
    /**
     * 获取原生的响应句柄
     * @return null|\swoole_http_response|mixed
     */
    public function getRawResponse(){
        return $this -> response;
    }
    /**
     * 请求是否已经结束
     * @return bool|mixed
     */
    public function ResponseIsEnd()
    {
        return $this -> is_end;
    }
    /**
     * 响应头
     * @param HeaderItem $headers
     * @return mixed|void
     */
    public function withHeader(HeaderItem $headers)
    {
        $this -> response_headers[] = $headers;
    }
    /**
     * 响应重定向 302
     * @param $url
     * @return mixed
     */
    public function redirect($url)
    {
        $this -> is_end = true;
    }
    /**
     * 向客户端写入内容
     * @param $content
     * @return mixed
     */
    abstract public function writeContent($content);
    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return mixed|void
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this -> response_http_code = $code;
    }
    /**
     * 结束请求
     * @return mixed
     */
    abstract public function endResponse();
    /**
     * 发送文件
     * @return mixed
     */
    public function sendFile($filename,$offset = 0,$length = 0)
    {
        /**
         * 标识响应已经结束
         */
        $this -> is_end = true;
        /**
         * 发送协议头
         */
        $this -> sendHeaders();
        /**
         * 发送Cookie
         */
        $this -> sendCookie();
        /**
         * 响应状态码
         */
        $this -> getRawResponse() -> status($this -> response_http_code);
    }

    /**
     * 打印内容
     * @param $var
     * @param mixed ...$moreVars
     * @return array
     */
    public static function dump($var, ...$moreVars)
    {
        if(!(Response::getInterface() instanceof Response)){
            return dump(...func_get_args());
        }
        $old_var_dump_foramt_value = isset($_SERVER['VAR_DUMPER_FORMAT'])?$_SERVER['VAR_DUMPER_FORMAT']:null;
        $_SERVER['VAR_DUMPER_FORMAT'] = 'html';
        ob_start();
        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        call_user_func(function ($var) use ($cloner, $dumper) {
            $dumper->dump($cloner->cloneVar($var));
        },$var);
        $content = ob_get_contents();
        ob_clean();
        $_SERVER['VAR_DUMPER_FORMAT'] = $old_var_dump_foramt_value;
        Response::getInterface() -> writeContent($content);
    }
}