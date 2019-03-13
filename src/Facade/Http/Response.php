<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;
use CloverSwoole\CloverSwoole\Facade\Hook\Hook;
use CloverSwoole\CloverSwoole\Facade\VarDumper\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * Http 响应基类
 * Class Response
 * @package CloverSwoole\CloverSwoole\Facade\Http
 */
abstract class Response implements \CloverSwoole\CloverSwoole\Facade\Http\Abstracts\Response
{
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
    protected static $interface = null;
    /**
     * 获取响应句柄
     * @return Response|null
     */
    public static function getInterface()
    {
        return static::$interface;
    }
    /**
     * 设置全局访问
     * @param $bool
     */
    public function setAsGlobal($bool = true)
    {
        if($bool){
            static::$interface = $this;
        }else{
            static::$interface = null;
        }
    }
    /**
     * 注入 Cookie
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     */
    public function withCookie(string $key, string $value = '', int $expire = 0 , string $path = '/', string $domain  = '', bool $secure = false , bool $httponly = false)
    {
        $this -> response_cookies[] = func_get_args();
    }
    /**
     * 请求是否已经结束
     * @return bool|mixed
     */
    public function responseIsEnd()
    {
        return $this -> is_end;
    }
    /**
     * 写入内容
     * @param string $content
     */
    public function withContent($content = '')
    {
        $this -> response_contents .= $content;
    }
    /**
     * 响应头
     * @param $name
     * @param $value
     */
    public function withHeader($name,$value)
    {
        $this -> response_headers[] = func_get_args();
    }

    /**
     * 响应重定向
     * @param string $url
     * @param int $http_code
     * @return mixed|void
     */
    public function redirect(string $url,int $http_code = 302)
    {
        $this -> endResponse();
        return $this -> sendRedirect(...func_get_args());
    }

    /**
     * 结束响应
     */
    public function endResponse()
    {
        if(is_array($this -> response_cookies) && count($this -> response_cookies)){
            foreach ($this -> response_cookies as $item){
                $this -> sendCookie(...$item);
            }
        }
        if(is_array($this -> response_headers) && count($this -> response_headers)){
            foreach ($this -> response_headers as $item){
                $this -> sendCookie(...$item);
            }
        }
        /**
         * 发送状态码
         */
        $this -> sendStatusCode($this -> response_http_code);
        /**
         * 标记响应已经结束
         */
        $this -> is_end = true;
        /**
         * 监听请求接口Hook
         */
        Hook::getInterface() -> listen('onResponseEnd');
    }
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
     * 打印内容
     * @param $var
     * @param mixed ...$moreVars
     * @return array
     */
    public static function dump($var, ...$moreVars)
    {
        if(!(static::getInterface() instanceof \CloverSwoole\CloverSwoole\Facade\Http\Abstracts\Response)){
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