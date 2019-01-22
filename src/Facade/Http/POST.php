<?php
namespace CloverSwoole\CloverSwoole\Facade\Http;
/**
 * Request POST
 * Class POST
 * @package CloverSwoole\CloverSwoole\Facade\Http
 */
class POST
{
    protected $post = [];
    /**
     * 构造器
     * Cookies constructor.
     */
    public function __construct()
    {

    }
    public function boot($post = [])
    {
        $this -> post = $post;
        return $this;
    }
    public function getPosts()
    {
        return $this -> post;
    }
    public function getPost($name)
    {
        return $name != null?(isset($this -> post[$name])?$this -> post[$name]:null):null;
    }
}