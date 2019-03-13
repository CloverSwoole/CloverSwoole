<?php
namespace CloverSwoole\CloverSwoole\Facade\Http\Exception;

/**
 * JSON 响应类捕获
 * Class Jons
 * @package CloverSwoole\CloverSwoole\Facade\Http\Exception
 */
class Jons extends ResponseBase
{
    public function __construct($data = '', array $headers = [], array $cookies = [])
    {
        parent::__construct($data, $headers, $cookies);
        $this -> data = json_encode($data,1);
        $this -> headers[] = ['Content-Type','application/json;charset=utf-8'];
        $this -> headers[] = ['Access-Control-Allow-Origin','*'];
        $this -> headers[] = ['Access-Control-Allow-Method','POST'];
        $this -> headers[] = ['Access-Control-Allow-Headers','accept, content-type'];
    }
}