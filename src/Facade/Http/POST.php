<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * Request POST
 * Class POST
 * @package Itxiao6\Framework\Facade\Http
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