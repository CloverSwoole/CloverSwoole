<?php
namespace Itxiao6\Framework\Facade\Route;
/**
 * Url 解析
 * Class UrlParser
 * @package Itxiao6\Framework\Facade\Route
 */
class UrlParser
{
    public static function pathInfo($path)
    {
        $basePath = dirname($path);
        $info = pathInfo($path);
        if($info['filename'] != 'index'){
            if($basePath == '/'){
                $basePath = $basePath.$info['filename'];
            }else{
                $basePath = $basePath.'/'.$info['filename'];
            }
        }
        return $path;
        return $basePath;
    }
}