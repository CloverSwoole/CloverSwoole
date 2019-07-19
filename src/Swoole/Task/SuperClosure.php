<?php
namespace CloverSwoole\Swoole\Task;
use SuperClosure\Serializer;

/**
 * 序列化工具包
 * Class SuperClosure
 * @package CloverSwoole\Swoole\Task
 */
class SuperClosure
{
    private $closure;
    private $serialized;

    function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    final public function __sleep()
    {
        $serializer = new Serializer();
        $this->serialized = $serializer->serialize($this->closure);
        unset($this->closure);
        return ['serialized'];
    }

    final public function __wakeup()
    {
        $serializer = new Serializer();
        $this->closure = $serializer->unserialize($this->serialized);
    }

    final public function __invoke()
    {
        // TODO: Implement __invoke() method.
        $args = func_get_args();
        return call_user_func($this->closure,...$args);
    }

    final function call(...$args)
    {
        return call_user_func($this->closure,...$args);
    }
}