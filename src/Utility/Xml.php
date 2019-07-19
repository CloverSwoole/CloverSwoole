<?php
namespace CloverSwoole\Utility;
/**
 * Class Xml
 * @package CloverSwoole\Utility
 */
class Xml
{

    /**
     * XML To Array
     * @param $xml
     * @return mixed
     */
    protected function xmlToArray($xml)
    {
        /**
         * 禁止引用外部xml实体
         */
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
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