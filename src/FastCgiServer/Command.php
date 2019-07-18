<?php
namespace CloverSwoole\Swoole\FastCgiServer;

/**
 * Class Command
 * @package CloverSwoole\Swoole\FastCgiServer
 */
class Command
{
    public static function getInterface()
    {
        return (new static(...func_get_args()));
    }
    public function run()
    {
        echo "111";
    }
}