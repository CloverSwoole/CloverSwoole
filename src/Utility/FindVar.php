<?php
namespace CloverSwoole\Utility;
/**
 * 查询并解决处理表达式
 * Class FindVar
 * @package CloverSwoole\Utility
 */
class FindVar
{
    /**
     * 根据表达式查找变量
     * @param null $expression
     * @param array $var
     * @return array|mixed
     */
    public static function findVarByExpression($expression = null,$var = [])
    {
        if($expression === null){
            return $var;
        }
        $array = self::query_expression($expression);
        if(count($array) >= 1){
            foreach ($array as $key=>$item){
                if($item == null || $item == '' || strlen($item) < 1){
                    return $var;
                }
                $var = isset($var[$item])?$var[$item]:null;
                if($var === null){
                    return null;
                }
            }
        }
        return $var;
    }

    /**
     * 查询处理字符串
     * @param string $string
     * @return array
     */
    protected static function query_expression($string = '')
    {
        return explode('.',$string);
    }
}