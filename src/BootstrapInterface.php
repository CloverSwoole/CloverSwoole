<?php
namespace CloverSwoole\CloverSwoole;
use Illuminate\Container\Container;

/**
 * 框架启动器
 * Interface BootstrapInterface
 * @package CloverSwoole\CloverSwoole
 */
interface BootstrapInterface
{
    /**
     * 初始化框架
     * @param Container|null $container
     * @return mixed
     */
    public function __construct(?Container $container = null);
}