<?php
namespace CloverSwoole\CloverSwoole\Facade\Utility;
/**
 * Class Xml
 * @package CloverSwoole\CloverSwoole\Facade\Utility
 */
class Xml
{
    /**
     * 数组转xml字符
     * @param $data
     * @return bool|string xml字符串
     */
    public static function arrayToXml($data){
        if(!is_array($data) || count($data) <= 0){
            return false;
        }
        $xml = "<xml>";
        foreach ($data as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
}