<?php
namespace CloverSwoole\CloverSwoole\Facade\Http\Exception;

/**
 * Xml 响应类捕获
 * Class Xml
 * @package CloverSwoole\CloverSwoole\Facade\Http\Exception
 */
class Xml extends ResponseBase
{
    public function __construct($data = '', array $headers = [], array $cookies = [])
    {
        parent::__construct($data, $headers, $cookies);
        $this -> data = \CloverSwoole\CloverSwoole\Facade\Utility\Xml::arrayToXml($data);
        $this -> headers[] = ['Content-Type','application/xml;charset=utf-8'];
        $this -> headers[] = ['Access-Control-Allow-Origin','*'];
        $this -> headers[] = ['Access-Control-Allow-Method','POST'];
        $this -> headers[] = ['Access-Control-Allow-Headers','accept, content-type'];
    }
}