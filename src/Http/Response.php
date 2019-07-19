<?php
namespace CloverSwoole\Http;
use CloverSwoole\VarDumper\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * Http 响应基类
 * Class Response
 * @package CloverSwoole\Http
 */
abstract class Response implements \CloverSwoole\Http\Abstracts\Response
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
    protected $response_http_code = 200;
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
     * @return $this
     */
    public function withContent($content = '')
    {
        $this -> response_contents .= $content;
        return $this;
    }

    /**
     * 置入响应头
     * @param $name
     * @param $value
     * @return $this
     */
    public function withHeader($name,$value)
    {
        $this -> response_headers[] = func_get_args();
        return $this;
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
        if($this -> is_end){
            return;
        }
        if(is_array($this -> response_cookies) && count($this -> response_cookies)){
            foreach ($this -> response_cookies as $item){
                $this -> sendCookie(...$item);
            }
        }
        if(is_array($this -> response_headers) && count($this -> response_headers)){
            foreach ($this -> response_headers as $item){
                $this -> sendHeader(...$item);
            }
        }
        /**
         * 获取缓存区内容
         */
        $content = ob_get_contents();
        /**
         * 关闭缓存区
         */
        if($content) ob_end_clean();
        /**
         * 判断缓存区内容是否有内容
         */
        if(strlen($content) >= 1){
            $this -> withContent($content);
        }
        /**
         * 结束响应时发送响应内容
         */
        if(strlen($this -> response_contents) > 0){
            $this -> sendContent($this -> response_contents);
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
         * 发送结束请求
         */
        $this -> sendEndResponse();
    }

    /**
     * 被动结束响应
     */
    public function __destruct()
    {
        if(!$this -> is_end){
            $this -> endResponse();
        }
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
        if(!(static::getInterface() instanceof \CloverSwoole\Http\Abstracts\Response)){
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
        Response::getInterface() -> sendContent($content);
    }
}