<?php

namespace CloverSwoole\Session;

use CloverSwoole\Session\Storage\File;

/**
 * 会话配置
 * Class SessionConfig
 * @package CloverSwoole\Session
 */
class SessionConfig
{
    /**
     * 配置实例
     * @var null | static
     */
    protected static $interface = null;
    /**
     * 配置内容
     * @var array
     */
    protected $config = [
        'cookieName' => 'CloverSwooleSeesionId',
        'sessionLength' => 50,
        'setCookie' => true,
        'storageDriver' => File::class,
        'storageConfig' => []
    ];

    /**
     * 获取配置
     * @param null $name
     * @return array|mixed|null
     */
    public function getConfig($name = null)
    {
        if ($name === null) {
            return $this->config;
        }
        return isset($this->config[$name]) ? $this->config[$name] : null;
    }

    /**
     * 设置配置
     * @param $name
     * @param $value
     * @return $this
     */
    public function setConfig($name, $value)
    {
        $this->config[$name] = $value;
        return $this;
    }

    /**
     * 获取配置接口
     * @return SessionConfig|null
     */
    public static function getInterface()
    {
        if (self::$interface === null) {
            return new static();
        }
        return self::$interface;
    }

    /**
     * 设置可全局访问
     * @return $this
     */
    public function setAsGlobal()
    {
        self::$interface = $this;
        return $this;
    }
}