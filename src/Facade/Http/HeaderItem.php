<?php
namespace Itxiao6\Framework\Facade\Http;
/**
 * Header Item
 * Class HeaderItem
 * @package Itxiao6\Framework\Facade\Http
 */
class HeaderItem
{
    protected $name = '';
    protected $value = '';
    public function __construct($name,$value)
    {
        $this -> name = $name;
        $this -> value = $value;
    }
    public function getName()
    {
        return $this -> name;
    }
    public function getValue()
    {
        return $this -> value;
    }
}