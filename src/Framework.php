<?php
namespace Itxiao6\Framework;
use Illuminate\Container\Container;

/**
 * 框架入口
 * Class Framework
 * @package Itxiao6\Framework
 */
class Framework
{
    public function start(?Container $container = null)
    {
        echo "Framework Started\n";
    }
}