<?php
namespace CloverSwoole\CloverSwoole\Facade\SuperClosure;
use SuperClosure\Serializer;

/**
 * 
 * Class SuperClosure
 * @package CloverSwoole\CloverSwoole\Facade\SuperClosure
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
        return Invoker::callUserFuncArray($this->closure,$args);
    }

    final function call(...$args)
    {
        return Invoker::callUserFuncArray($this->closure,$args);
    }
}