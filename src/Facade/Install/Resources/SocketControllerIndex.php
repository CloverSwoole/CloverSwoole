<?php
namespace App\Socket\Index;
use CloverSwoole\CloverSwoole\Facade\Socket\Abstracts\SocketController;
use CloverSwoole\CloverSwoole\Facade\SwooleSocket\SocketFrame;

/**
 * Class Index
 * @package App\Socket\Index
 */
class Index extends SocketController
{
    public function index()
    {
        $this -> returnJosn(SocketFrame::getInterface() ->getData());
    }
}